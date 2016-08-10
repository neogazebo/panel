<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'https' => [
                'class' => \app\components\filters\Https::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(Yii::$app->urlManager->createUrl(['dashboard']));
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $checkRoute = \app\components\filters\AccessFilters::checkRoute();
            if ($checkRoute == 'merchantSG') {
                $defaultRoute = Yii::$app->urlManager->createUrl(['merchant-signup']);
            // } elseif($checkRoute == 'snapearn') {
            } else {
                $defaultRoute = Yii::$app->urlManager->createUrl(['snapearn']);
            // } else {
            //     $defaultRoute = Yii::$app->urlManager->createUrl(['dashboard']);
            }

            return $this->redirect($defaultRoute);

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
