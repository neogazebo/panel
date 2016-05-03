<?php

namespace app\modules\users\controllers;

use yii\web\Controller;

/**
 * Default controller for the `users` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
