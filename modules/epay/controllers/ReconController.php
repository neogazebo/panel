<?php

/*
 */

namespace app\modules\epay\controllers;

use Yii;
use yii\web\Controller;
use app\models\EpayDetail;
use yii\helpers\BaseFileHelper;

/**
 * Description of ReconController
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class ReconController extends Controller
{
    private $ftpEpayServer = 'sftp.e-pay.com.my';
    private $ftpEpayServerPort = 22;
    private $ftpEpayServerUsername = 'ebizu';
    private $ftpEpayServerPassword = 'Mbr6khXJ79kAY';

    public function init()
    {
        parent::init();
        date_default_timezone_set('Etc/UTC');
    }
    
    public function behaviors()
    {
        return [
            'https' => [
                'class' => \app\components\filters\Https::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * Epay Manual Recon
     */
    public function actionManualRecon($data = 'today')
    {
        $model = new EpayDetail();
        $date = null;
        $recapType = rtrim($data, '/');
        // echo $recapType;exit;
        $filename = null;
        $date = date('Ymd', (strtotime('-1 day', strtotime(date('Ymd')))));
        if ($recapType == 'today') {
            $date = date('Ymd', (strtotime(date('Ymd'))));
            $fileName = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
        } else if ($recapType == 'specific') {
            $postDate = $_POST['date'];
            $formatDate = str_replace('/','',strtotime($postDate));
            $fileDate = date('ymd',$formatDate);
            $date = date('Ymd',$formatDate);
            $filename = EpayDetail::CLIENT_SHORTNAME . $fileDate . '.csv';
        }
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        self::_out($model->getReconciliationData($recapType, $date));
    }

    /*
     * Rekonsiliasi yg langsung di upload ke server ftp epay
     */
    public function actionFtp()
    {
        $model = new EpayDetail();
        $recapType = 'today';
        $date = null;
        $return = array();
        $filename = null;

        if (isset($_POST['date'])) {
            $recapType = 'specific';
            $postDate = $_POST['date'];
            $formatDate = str_replace('/','',strtotime($postDate));
            $fileDate = date('ymd',$formatDate);
            $date = date('Ymd',$formatDate);
            $filename = EpayDetail::CLIENT_SHORTNAME . $fileDate . '.csv';
        }else{
            $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
        }

        // get data from table EpayDetail
        $data = $model->getReconciliationData($recapType, $date);

        // create local directory
        $dir = Yii::$app->basePath."/runtime/sFTp/";

        if(!is_dir($dir)){
            $create = BaseFileHelper::createDirectory ( $dir, $mode = 509, $recursive = true );
        }

        // create filename on local directory
        $output = fopen(Yii::$app->basePath."/runtime/sFTp/$filename", 'w');
        if ($output === false) {
            Yii::$app->session->setFlash('error','Unable to write file on remote server.', 'attachment'.null);
        } else {

            // write value of csv file
            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            // upload to server epay
            // $upload = Yii::$app->ftp->put(Yii::$app->basePath."/runtime/sFTp/$filename","/recon/$filename");
            // Yii::$app->session->setFlash('success','Recon file successfully uploaded with name : ' . $filename, 'attachment'.$filename);
        }

        // delete local dir
        // $delete = BaseFileHelper::removeDirectory(Yii::$app->basePath."/runtime/sFTp",$options = false);

        return $this->redirect(Yii::$app->urlManager->createUrl(['epay/index/']));
    }
    
    public function actionCronService()
    {
        $model = new EpayDetail();
        $recapType = 'today';
        $date = null;
        $return = array();
        $filename = null;
        $tx=    microtime(true);
        //prosessss
        $rx= microtime(true);
        $elapsed = number_format($rx - $tx, 2) . 'secs';

        if (isset($_POST['date'])) {
            $postDate = explode('/', $_POST['date']);
            $date = $postDate[2] . $postDate[1] . $postDate[0];
            $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', strtotime($date)) . '.csv';
        }else{
            $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
        }

        // get data from table EpayDetail
        $data = $model->getReconciliationData($recapType, $date);
        exit;
        // create local directory
        $dir = Yii::$app->basePath."/runtime/sFTp/";

        if(!is_dir($dir)){
            $create = BaseFileHelper::createDirectory ( Yii::$app->basePath."/runtime/sFTp", $mode = 509, $recursive = true );
        }

        // create filename on local directory
        $output = fopen(Yii::$app->basePath."/runtime/sFTp/$filename", 'w');
        if ($output === false) {
            $return['data'] = array('code' => 505, 'message' => 'Unable to write file on remote server.', 'attachment' => null,'date' => date('Ymd H:i:s'),'time-execute' => $elapsed);
        } else {

            // write value of csv file
            foreach ($data as $row) {
                fputcsv($output, $row);
            }

            // upload to server epay
            $upload = Yii::$app->ftp->put(Yii::$app->basePath."/runtime/sFTp/$filename","/recon/$filename");

            $return['data'] = array('code' => 200, 'message' => 'Recon file successfully uploaded with name : ' . $filename, 'attachment' => $filename,'date' => date('Y-m-d H:i:s'),'execute' => $elapsed);
        }

        // delete local dir
        $delete = BaseFileHelper::removeDirectory(Yii::$app->basePath."/runtime/sFTp",$options = false);

        echo json_encode($return);
    }
    
    private static function _out($data)
    {
        $output = fopen("php://output", "w");
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }
      

}
