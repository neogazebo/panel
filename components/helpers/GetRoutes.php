<?php

namespace app\components\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\caching\TagDependency;
use yii\web\Response;
use app\models\AuthItem;
use app\models\AuthRule;

class GetRoutes
{
	public function generatePermission()
    {
        echo Yii::$app->user->id;exit;
        $routes = $this->searchRoute('all');
        $insert = 0;
        foreach ($routes as $route => $status) {
            if(!AuthItem::findOne($route)) {
                $auth = Yii::$app->authManager;
                $pos = (strrpos($route, '/'));
                $alias = substr($route, $pos + 1, 100);
                $permissions = $auth->createPermission($route);
                $permissions->description = $alias;
                if($auth->add($permissions)){
                    $model = AuthItem::findOne($route);
                    $model->created_by = Yii::$app->user->id;
                    if($model->save()){
                        $insert++;
                    }
                }
            }
        }
        $result = [
            'status' => 'success',
        ];
        return $result;
    }

    public function searchRoute($target, $term = '', $refresh = '0')
    {
        if ($refresh == '1') {
            $this->invalidate();
        }
        $result = [];
        $manager = Yii::$app->getAuthManager();

        $exists = array_keys($manager->getPermissions());
        $routes = $this->getAppRoutes();
        if ($target == 'available') {
            foreach ($routes as $route) {
                if (in_array($route, $exists)) {
                    continue;
                }
                if (empty($term) or strpos($route, $term) !== false) {
                    $result[$route] = true;
                }
            }
        } else if ($target == 'all') {
            foreach ($routes as $route) {
                $available = 0;
                if (in_array($route, $exists)) {
                    $available = 1;
                }
                if (empty($term) or strpos($route, $term) !== false) {
                    $result[$route] = $available;
                }
            }
        } else {
            foreach ($exists as $name) {
                if ($name[0] !== '/') {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $r = explode('&', $name);
                    $result[$name] = !empty($r[0]) && in_array($r[0], $routes);
                }
            }
        }

        Yii::$app->response->format = 'json';
        return $result;
    }

    /**
     * Get list of application routes
     * @return array
     */
    public function getAppRoutes()
    {
        $key = __METHOD__;
        $cache = null;
        if ($cache === null || ($result = $cache->get($key)) === false) {
            $result = [];
            $this->getRouteRecrusive(Yii::$app, $result);
            if ($cache !== null) {
                $cache->set($key, $result, Configs::instance()->cacheDuration, new TagDependency([
                    'tags' => self::CACHE_TAG
                ]));
            }
        }

        return $result;
    }

    /**
     * Get route(s) recrusive
     * @param \yii\base\Module $module
     * @param array $result
     */
    private function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if($module->uniqueId !== 'debug' && $module->uniqueId !== 'gii') {
                foreach ($module->getModules() as $id => $child) {
                    if (($child = $module->getModule($id)) !== null) {
                        $this->getRouteRecrusive($child, $result);
                    }
                }

                foreach ($module->controllerMap as $id => $type) {
                    $this->getControllerActions($type, $id, $module, $result);
                }

                $namespace = trim($module->controllerNamespace, '\\') . '\\';
                $this->getControllerFiles($module, $namespace, '', $result);
                // $result[] = ($module->uniqueId === '' ? '/' : '/'. $module->uniqueId .'/*');
                if ($module->uniqueId !== '') {
                    $result[] = '/'. $module->uniqueId .'/*';
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    private function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = @Yii::getAlias('@' . str_replace('\\', '/', $namespace));
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if($module->uniqueId !== 'debug' && $module->uniqueId !== 'gii') {
                if (!is_dir($path)) {
                    return;
                }
                foreach (scandir($path) as $file) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    if (is_dir($path . '/' . $file)) {
                        $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                    } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                        $id = Inflector::camel2id(substr(basename($file), 0, -14));
                        $className = $namespace . Inflector::id2camel($id) . 'Controller';
                        if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                            $this->getControllerActions($className, $prefix . $id, $module, $result);
                        }
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

 
    private function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            $this->getActionRoutes($controller, $result);
            $result[] = '/' .$controller->uniqueId . '/*';
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }


    private function getActionRoutes($controller, &$result)
    {
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/'. $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                $result[] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $result[] = $prefix . Inflector::camel2id(substr($name, 6));
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

}
