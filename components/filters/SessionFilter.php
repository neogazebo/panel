<?php

namespace app\components\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;
use yii\web\ForbiddenHttpException;
use app\models\Session;

class SessionFilter extends ActionFilter
{
    public $user;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->user->identity;
        if (!Session::check($this->user->id, Yii::$app->session->get('ebz_session_key'))) {
            Yii::$app->user->logout();
            return Yii::$app->controller->redirect(Yii::$app->urlManager->createAbsoluteUrl('/auth/login'));
        }
    }

}
