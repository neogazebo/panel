<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WorkingTime;
use app\models\Logging;
use app\components\helpers\General;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Style_Font;

class BaseController extends Controller
{
    public $page_size = 20;
    public $enableCsrfValidation = false;
    public $user;

    protected $output_type;
    protected $output;
    protected $data_provider;
    protected $data_hooks = [];

    public function beforeAction($action)
    {
		// if (!parent::beforeAction($action)) {
		//     return false;
		// }
		if (Yii::$app->user->isGuest) {
			$this->redirect(Yii::$app->urlManager->createUrl(['site/login/']));
		    return false;
		}
		$this->user = Yii::$app->user->identity;
		return true;
    }

    public function behaviors()
    {
		return [
		    'https' => [
				'class' => \app\components\filters\Https::className(),
		    ],
            'access' => [
                'class' => \app\components\filters\AccessFilters::className(),
            ],
		];
    }
    
    // set up global session using params
    public function setSession($name,$params)
    {
        return \Yii::$app->session->set($name,$params);
    }
    
    // get global session using params
    public function getSession($params)
    {
        return \Yii::$app->session->get($params);
    }
    
    // destory global session using params
    public function removeSession($params)
    {
        return \Yii::$app->session->remove($params);
    }

        public function setRememberUrl()
    {
		return \Yii::$app->session->set('rememberUrl', Yii::$app->request->url);
    }

    public function getRememberUrl()
    {
		return \Yii::$app->session->get('rememberUrl');
    }

    public function setMessage($key, $type, $customText = null)
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

    public function saveLog($activities)
    {
        // ['activity', 'desc', 'item', 'item_id']
        $loging = new Logging;
        $loging->saveLog($activities);
    }

    protected function centralTimeZone()
    {
        return date_default_timezone_set('UTC');
    }
    
    public function workingTime()
    {
        $this->centralTimeZone();
        return microtime(true);
    }

    public function saveWorking($id)
    {
        $model = new WorkingTime();
        $wrk_ses = $this->getSession('wrk_ses_'.$id);
        $model->wrk_type = $wrk_ses['wrk_type'];
        $model->wrk_by = $wrk_ses['wrk_by'];
        $model->wrk_param_id = $wrk_ses['wrk_param_id'];
        $model->wrk_start = $wrk_ses['wrk_start'];
        $model->wrk_end = $this->workingTime();
        $model->wrk_time = ($model->wrk_end - $model->wrk_start);
        $model->wrk_description = $wrk_ses['wrk_description'];
        $model->wrk_point = $wrk_ses['wrk_point'];
        $model->wrk_point_type = $wrk_ses['wrk_point_type'];
        $model->wrk_rjct_number = $wrk_ses['wrk_rjct_number'];
        $model->save();
    }

//    public function cancelWorking($id)
//    {
//        $model = WorkingTime::find()->where('wrk_param_id = :id AND wrk_by = :user',[
//                ':id' => $id,
//                ':user' => Yii::$app->user->id
//            ])->one();
//        $model->delete();
//
//    }

    protected function processOutputType()
    {
        $this->output_type = Yii::$app->request->get('output_type');
    }

    protected function processOutputHooks($data)
    {
        foreach($data as $key => $value)
        {
            $this->data_hooks[$key] = $value;
        }
    }

    protected function processOutputSize($size =  0)
    {
        $this->page_size = 20;

        if (!empty($size)) {
            $this->page_size = $size;
        }
        if($this->output_type)
        {
            if($this->output_type == 'excel')
            {
                $this->page_size = 0;
            }
        }
    }

    protected function processOutput($view_name, $excel_columns, $excel_column_styles, $save_path, $filename)
    {
        // comes from search or export event
        if($this->output_type)
        {
            if($this->output_type == 'view')
            {
                return $this->render($view_name, [
                    'dataProvider' => $this->data_provider,
                    'data_hooks' => $this->data_hooks
                ]);
            }

            return $this->exportToExcel($excel_columns, $excel_column_styles, $save_path, $filename);
        }

        // this is executed the first time user open the sne page
        return $this->render($view_name, [
            'dataProvider' => $this->data_provider,
            'data_hooks' => $this->data_hooks
        ]);
    }

    private function exportToExcel($columns, $column_styles, $save_path, $filename)
    {   
        // the data providers could be a model or query class
        // so we have to check
        $models = method_exists($this->data_provider,'getModels') ?  $this->data_provider->getModels() : $this->data_provider;

        $excel = new PHPExcel();

        $excel->setActiveSheetIndex(0);

        foreach($columns as $key => $column)
        {
            $excel->getActiveSheet()->getColumnDimension($key)->setWidth($column['width']);
            $excel->getActiveSheet()->getRowDimension($key)->setRowHeight($column['height']);
            
            $excel->getActiveSheet()->SetCellValue($key . '1', $column['name']);
            $excel->getActiveSheet()->getStyle($key . '1')->applyFromArray($column_styles);
            $excel->getActiveSheet()->getStyle($key)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        }

        $row = 2;

        foreach($models as $data)
        {
            foreach($columns as $key => $column)
            {
                $value = $data->{$column['db_column']};

                if(isset($column['format']))
                {
                    $value = $column['format']($data->{$column['db_column']});
                }

                if(isset($column['have_relations']))
                {
                    $value = $data->{$column['db_column']} ? $data->{$column['db_column']}->{$column['relation_name']} : '';
                }
                
                $excel->getActiveSheet()->SetCellValue($key . $row, $value);   
            }

            $row++;
        }

        $writer = new PHPExcel_Writer_Excel2007($excel);

        $root_path = 'excel_report';

        if (!is_dir($root_path)) 
        {
            mkdir($root_path);         
        }

        if (!is_dir($root_path . '/' . $save_path)) 
        {
            mkdir($root_path . '/' . $save_path);         
        }

        $excel_report_path = $root_path . '/' . $save_path . '/' . $filename;

        $writer->save($excel_report_path);

        Yii::$app->response->sendFile($excel_report_path);
    }

    protected function jsonOutput($code, $status, $message, $data = null)
    {
        return [
            'error' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
