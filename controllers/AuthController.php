<?php
namespace app\controllers;

/**
* 
*/
use Yii;
use yii\web\UploadedFile;
use yii\helpers\Json;
use app\models\LoginForm;
// use app\components\helpers\General;
use app\models\User;

class AuthController extends GuestController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionLogin()
    {
        $this->layout   = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            // var_dump($model->attributes);
            // exit;
            if($model->login())
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('epay/index'));
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}