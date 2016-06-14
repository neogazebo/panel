<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WorkingTime;

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

    public function startWorking($user,$type,$param)
    {
    	$model = new WorkingTime();
    	$model->wrk_type = $type;
    	$model->wrk_by = $user;
    	$model->wrk_param_id = $param;
    	$model->wrk_start = time();
    	if ($model->save()) {
    		return $model->wrk_id;
    	}
    }

    public function endWorking($id,$desc)
    {
    	$model = WorkingTime::findOne($id);
    	$model->wrk_description = $desc;
    	$model->wrk_end = time();
    	$model->save();
    }

}
