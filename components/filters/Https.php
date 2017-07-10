<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;
use yii\web\ForbiddenHttpException;

class Https extends ActionFilter
{
    public $allowedIPs = ['127.0.0.1', '::1'];

    public function init()
    {
        parent::init();
        if (!Yii::$app->getRequest()->isSecureConnection && !in_array(Yii::$app->getRequest()->getUserIP(), $this->allowedIPs)) {
            $url = 'https://' .
                Yii::$app->getRequest()->serverName .
                Yii::$app->getRequest()->url;
            return Yii::$app->controller->redirect($url);
        }
    }

}
