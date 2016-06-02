<?php

namespace app\modules\snapearn\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\models\SnapEarn;

/**
 * Default controller for the `snapearn` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->setRememberUrl();
        $model = SnapEarn::find()->orderBy('sna_upload_date DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
        	'dataProvider' => $dataProvider
    	]);
    }

    public function actionToUpdate($id)
    {
        Yii::$app->workingTime->start($id);
        return $this->redirect(['snapearnupdate', 'id' => $id]);
    }

    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);    	
    }

    protected function findModel($id)
    {
    	if (($model = SnapEarn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
