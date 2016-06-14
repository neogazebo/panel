<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\components\helpers\CropProcessor;

class CropperController extends Controller {

    public function init() {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function actionUpload() {
        header("access-control-allow-origin: *");
        CropProcessor::clearLocal();
        echo json_encode(CropProcessor::upload());
    }

    public function actionProcess() {
        header("access-control-allow-origin: *");
        switch (strtolower(Yii::$app->request->post('action'))) {
            case 'crop' :
                echo json_encode(CropProcessor::crop());
                break;
            case 'skip' :
                if (intval(Yii::$app->request->post('skip_and_resize')) === 1)
                    echo json_encode(CropProcessor::skip());
                else
                    echo json_encode(CropProcessor::duplicate());
                break;
        }
    }

    // publish to s3
    public function actionPublish() {
        header("access-control-allow-origin: *");
        echo json_encode(CropProcessor::publish());
    }

    public function actionClear() {
        header("access-control-allow-origin: *");
        CropProcessor::clearLocal();
    }

}
