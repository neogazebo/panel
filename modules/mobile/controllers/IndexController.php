<?php

namespace app\modules\mobile\controllers;

use yii\web\Controller;

/**
 * Default controller for the `mobile` module
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
