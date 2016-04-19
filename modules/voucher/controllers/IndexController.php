<?php

namespace app\modules\voucher\controllers;

use yii\web\Controller;
use app\controllers\BaseController;
/**
 * Default controller for the `voucher` module
 */
class IndexController extends BaseController
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
