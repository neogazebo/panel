<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $page_size = 20;
    public $enableCsrfValidation = false;
    public $user;

    public function init()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
        }
        $this->user = Yii::$app->user->identity;
        return true;
    }
    
    public function behaviors()
    {
        return [
            'permission' => [
                // 'class' => \common\components\filters\AdminPermissionFilter::className(),
            ],
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
}
