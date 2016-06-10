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
            // $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
            $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime(date('Ymd')))) . '.csv';
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

            $return['data'] = array('code' => 200 .PHP_EOL, 'message' => 'Recon file successfully uploaded with name : ' . $filename.PHP_EOL, 'attachment' => $filename.PHP_EOL,'date' => date('Y-m-d H:i:s').PHP_EOL,'execute' => $elapsed.PHP_EOL);
        }

        // delete local dir
        $delete = BaseFileHelper::removeDirectory(Yii::$app->basePath."/runtime/sFTp",$options = false);

        echo json_encode($return);
    }
}
