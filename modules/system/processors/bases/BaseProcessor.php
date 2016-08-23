<?php

namespace app\modules\system\processors\bases;

use Yii;
use yii\web\Response;
use app\controllers\BaseController;
use app\components\helpers\JsonHelper;

abstract class BaseProcessor extends BaseController
{
    protected $json_helper;

    const VALIDATION_ERROR_CODE = 9000;
    const SYSTEM_ERROR_CODE = 1000;
    const CHILDREN_OP_CONFIRMATION_CODE = 2000;

    public function __construct()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->json_helper = new JsonHelper();
    }

    abstract public function process();
}