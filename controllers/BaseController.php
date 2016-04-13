<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public $page_size = 20;
    public $enableCsrfValidation = false;
    public $user;

//    public function init()
//    {
//        if (Yii::$app->user->isGuest) {
////            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
//            return $this->redirect(['/site/login/']);
//        }
//        $this->user = Yii::$app->user->identity;
//        return true;
//    }

    public function beforeAction($action)
    {
<<<<<<< HEAD
        if (Yii::$app->user->isGuest) {
            // return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
            // exit(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
            return $this->redirect("https://www.google.co.id");
        }
        $this->user = Yii::$app->user->identity;
        return true;
=======
	if (!parent::beforeAction($action))
	{
	    return false;
	}
	if (Yii::$app->user->isGuest)
	{
	    $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
	    return false;
	}
	$this->user = Yii::$app->user->identity;
	return true;
>>>>>>> 9e4bebb424e8a0d04aff59d54fd6a32c426ae745
    }

    public function behaviors()
    {
	return [
	    // 'session' => [
	    //     'class' => \app\components\filters\SessionFilter::className(),
	    // ],
	    'https' => [
		'class' => \app\components\filters\Https::className(),
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
	switch ($key)
	{
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

}
