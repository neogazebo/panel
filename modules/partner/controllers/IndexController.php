<?php

namespace app\modules\partner\controllers;

use yii\web\Controller;

/**
 * Default controller for the `partner` module
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
