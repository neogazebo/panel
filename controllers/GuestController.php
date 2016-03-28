<?php

/*
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * @author Ilham Fauzi ilham@ebizu.com
 */
class GuestController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    protected function setMessage($key, $type, $customText = null)
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

    protected function changeDate($date)
    {
        $datePart = explode('to', $date);
        if (sizeof($datePart) > 1) {
            $date1 = date('d-m-Y', strtotime($datePart[0]));
            $date2 = date('d-m-Y', strtotime($datePart[1]));
            $date = $date1 . ' 00:00 - ' . $date2 . ' 23:59';
            return $date;
        } else {
            return $date;
        }
    }

    protected function getDate()
    {
        $date = \app\models\Setting::GETDate();
        if (isset($_GET['date'])) {
            $date = $_GET['date'];
        }
        $date = $this->changeDate($date);
        return $date;
    }
}
