<?php

namespace app\modules\merchant_signup\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\controllers\BaseController;
use app\models\Company;
use app\models\User;

/**
 * Default controller for the `MerchantSignup` module
 * @author Ganjar Setia M <ganjar.setia@ebizu.com>
 */
class InquiryController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	Url::remember();
        $this->processOutputType();
        $this->processOutputSize();

        $model = Company::find()->listInquiry;
        $model->joinWith(['category' => function($model) {
        	$model->from(['category' => 'tbl_company_category']);
        }]);
        $model->joinWith(['userCreated' => function($model) {
        	$model->from(['userCreated' => 'tbl_admin_user']);
        }]);
        $this->data_provider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'attributes' => [
                	'com_name',
                	'com_email',
                	'com_city',
                	'com_created_date'
                ],
            ],
            'pagination' => [
                'pageSize' => $this->page_size
            ]
        ]);

        $columns = Company::find()->getExcelColumns();
        $column_styles = Company::find()->getExcelColumnsStyles();

        $filename = 'COMPANY-' . date('Y-m-d H:i:s', time()) . '.xlsx';

        $view_filename = 'index';
        $save_path = 'merchant';

        // additional views output goes here
        // $this->getMerchantName();

        return $this->processOutput($view_filename, $columns, $column_styles, $save_path, $filename);
    }

    public function actionUserList()
    {
    	if (Yii::$app->request->isAjax) {
    		$model = User::find()->findUser();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->id, 'value' => $d->username];
            }
            echo \yii\helpers\Json::encode($out);
    	}
    }

    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            $model = Company::find()->searchExistingMerchant();
            $out = [];
            if (!empty($model)) {
                foreach ($model as $d) {
                    $out[] = [
                        'id' => $d->com_id,
                        'value' => $d->com_name
                    ];
                }
            } else {
                $out[] = [
                    'id' => 0,
                    'value' => 'Merchant Not Found!',
                ];
            }
            echo \yii\helpers\Json::encode($out);
        }
    }

}
