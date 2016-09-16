<?php

    namespace app\modules\rbac\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\data\ActiveDataProvider;
    use yii\web\NotFoundHttpException;
    use app\models\User;
    use app\controllers\BaseController;

    use app\modules\rbac\processors\RbacGetModuleComponentsProcessor;
    use app\modules\rbac\processors\RbacSaveModulePermissionsProcessor;

    /**
     * Default controller for the `rbac` module
     */
    class PermissionController extends BaseController
    {
        public function actionIndex()
        {
            $module_names = Yii::$app->mod_component_helper->getModuleNames();

            return $this->render('index', [
                'module_names' => $module_names
            ]);
        }

        public function actionGetModuleComponents()
        {
            $processor = new RbacGetModuleComponentsProcessor();
            return $processor->process();
        }

        public function actionSaveModulePermissions()
        {
            $processor = new RbacSaveModulePermissionsProcessor();
            return $processor->process();
        }
    }
