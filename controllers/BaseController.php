<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WorkingTime;
use app\components\helpers\General;

class BaseController extends Controller
{
    public $page_size = 20;
    public $enableCsrfValidation = false;
    public $user;

    public function beforeAction($action)
    {
		// if (!parent::beforeAction($action)) {
		//     return false;
		// }
		if (Yii::$app->user->isGuest) {
			$this->redirect(Yii::$app->urlManager->createUrl(['site/login/']));
		    return false;
		}
		$this->user = Yii::$app->user->identity;
		return true;
    }

    public function behaviors()
    {
		return [
		    'https' => [
				'class' => \app\components\filters\Https::className(),
		    ],
            'access' => [
                'class' => \app\components\filters\AccessFilters::className(),
            ],
		];
    }
    
    // set up global session using params
    public function setSession($name,$params)
    {
        return \Yii::$app->session->set($name,$params);
    }
    
    // get global session using params
    public function getSession($params)
    {
        return \Yii::$app->session->get($params);
    }
    
    // destory global session using params
    public function removeSession($params)
    {
        return \Yii::$app->session->remove($params);
    }

        public function setRememberUrl()
    {
		return \Yii::$app->session->set('rememberUrl', Yii::$app->request->url);
    }

    public function getRememberUrl()
    {
		return \Yii::$app->session->get('rememberUrl');
    }

    public function setMessage($key, $type, $customText = null)
    {
		switch ($key) {
		    case 'save' :
				Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['save'][$type]);
				break;
		    case 'update' :
				Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['update'][$type]);
				break;
		    case 'delete' :
				Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['delete'][$type]);
				break;
		}
    }

    protected function centralTimeZone()
    {
        return date_default_timezone_set('UTC');
    }

    public function checkingWrk($param)
    {
        $model = WorkingTime::find()->findWorkExist($param)->one();
        return $model;
    }

    public function startWorking($user,$param,$point_type,$point)
    {
        $this->centralTimeZone();
        // checking existing worktime with this user and param id
    	$model = WorkingTime::find()->findWorkExist($param,$point_type)->one();
        // if there is no exists worktime create this one
        if (empty($model)) {
            $model = new WorkingTime();
            $model->wrk_by = $user;
            $model->wrk_param_id = $param;
            $model->wrk_point = $point;
            $model->wrk_point_type = $point_type;
            $model->wrk_start = microtime(true);
            if ($model->save(false)) {
                return $model->wrk_id;
            }
        }elseif (empty($model->wrk_end)) {
            $model->wrk_by = $user;
            $model->wrk_param_id = $param;
            $model->wrk_point = $point;
            $model->wrk_start = microtime(true);
            if ($model->save(false)) {
                return $model->wrk_id;
            }
        }
        return $model->wrk_id;
    }

    public function addWorkPoint($param,$point)
    {
        $this->centralTimeZone();
        $model = WorkingTime::find()->findWorkExist($param)->one();
        if (!empty($model)) {
            $model->wrk_point = $model->wrk_point + $point;
            $model->save(false);
        }
    }

    public function endWorking($id,$type,$desc,$sem_id = 0)
    {
        $this->centralTimeZone();
    	$model = WorkingTime::findOne($id);
        $model->wrk_type = (int)$type;
    	$model->wrk_description = $desc;
    	$model->wrk_end = microtime(true);
        $model->wrk_time = ($model->wrk_end - $model->wrk_start);
        $model->wrk_rjct_number = $sem_id;
    	$model->save(false);
    }

    public function cancelWorking($id)
    {
        $model = WorkingTime::find()->where('wrk_param_id = :id AND wrk_by = :user',[
                ':id' => $id,
                ':user' => Yii::$app->user->id
            ])->one();
        $model->delete();

    }

}
