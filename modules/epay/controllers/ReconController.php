<?php

/*
 */

namespace app\modules\epay\controllers;

use Yii;
use yii\web\Controller;
use app\models\EpayDetail;

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
        date_default_timezone_set('Asia/Jakarta');
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
        echo 'work';
    }

    /*
     * Epay Manual Recon
     */
    public function actionManualRecon($data = 'today')
    {
        $model = new EpayDetail();

        $recapType = rtrim($data, '/');
        $fileName = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
        $date = date('Ymd', (strtotime('-1 day', strtotime(date('Ymd')))));
        if ($recapType == 'today') {
            $date = date('Ymd', (strtotime(date('Ymd'))));
        } else if ($recapType == 'specific') {
            $postDate = explode('/', $_POST['date']);
            $date = $postDate[2] . $postDate[1] . $postDate[0];
            $fileName = EpayDetail::CLIENT_SHORTNAME . date('ymd', strtotime($postDate[2] . $postDate[1] . $postDate[0])) . '.csv';
        }
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $fileName);
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
        if (!function_exists('ssh2_connect')) {
            // throw new Exception("ssh2 library not found.");
            // $return['data'] = array('code' => 505, 'message' => 'ssh2 library not found.', 'attachment' => null);
            $this->setMessage('save', 'error', 'ssh2 library not found.');
        } else {
            $resConnection = ssh2_connect($this->ftpEpayServer, $this->ftpEpayServerPort);
            if (ssh2_auth_password($resConnection, $this->ftpEpayServerUsername, $this->ftpEpayServerPassword)) {
                $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
                if (isset($_POST['date'])) {
                    $postDate = explode('/', $_POST['date']);
                    $date = $postDate[2] . $postDate[1] . $postDate[0];
                    // format filename
                    $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', strtotime($date)) . '.csv';
                }
                $data = $model->getReconciliationData($recapType, $date);

                // Initialize SFTP subsystem
                $resSFTP = ssh2_sftp($resConnection);

                $output = fopen("ssh2.sftp://{$resSFTP}/recon/$filename", 'w');

                if ($output === false) {
                    // $this->setMessage('save', 'error', 'Unable to write file on remote server.');
                    // $return['data'] = array('code' => 505, 'message' => 'Unable to write file on remote server.', 'attachment' => null);
                } else {
                    foreach ($data as $row) {
                        fputcsv($output, $row);
                    }
                    fclose($output);
                    // $return['data'] = array('code' => 200, 'message' => 'Recon file successfully uploaded with name : ' . $filename, 'attachment' => $filename);
                    $this->setMessage('save', 'success', 'Recon file successfully uploaded with name : ' . $filename);
                }
            } else {
                // $return['data'] = array('code' => 500, 'message' => 'Unable to authenticate on server.', 'attachment' => null);
                $this->setMessage('save', 'error', 'Unable to authenticate on server.');
            }
        }
        // echo json_encode($return);
        return $this->redirect(Yii::$app->urlManager->createUrl(['epay/index/']));
    }


    public function actionTestCron()
    {
        // echo Yii::$app->basePath.'/runtime/sftp/';exit;
        $model = new EpayDetail();
        $recapType = 'today';
        $date = null;
        $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
        if (isset($_POST['date'])) {
            $postDate = explode('/', $_POST['date']);
            $date = $postDate[2] . $postDate[1] . $postDate[0];
            // format filename
            $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', strtotime($date)) . '.csv';
        }
        $data = $model->getReconciliationData($recapType, $date);

        $output = fopen(Yii::$app->basePath."/runtime/sftp/$filename", 'w');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        $put = Yii::$app->ftp->put(Yii::$app->basePath."/runtime/sftp/$filename",Yii::$app->basePath."/runtime/remote/$filename");
        echo $put;
        // return $this->render('index');
    }
    
    public function actionCronService()
    {
        $model = new EpayDetail();
        $recapType = 'today';
        $date = null;
        $return = array();
        if (!function_exists('ssh2_connect')) {
            $return['data'] = array('code' => 505, 'message' => 'ssh2 library not found.', 'attachment' => null);
        } else {
            $resConnection = ssh2_connect($this->ftpEpayServer, $this->ftpEpayServerPort);

            if (ssh2_auth_password($resConnection, $this->ftpEpayServerUsername, $this->ftpEpayServerPassword)) {
                $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', (strtotime('-1 day', strtotime(date('Ymd'))))) . '.csv';
                if (isset($_POST['date'])) {
                    $postDate = explode('/', $_POST['date']);
                    $date = $postDate[2] . $postDate[1] . $postDate[0];
                    // format filename
                    $filename = EpayDetail::CLIENT_SHORTNAME . date('ymd', strtotime($date)) . '.csv';
                }
                $data = $model->getReconciliationData($recapType, $date);

                //Initialize SFTP subsystem
                $resSFTP = ssh2_sftp($resConnection);

                $output = fopen("ssh2.sftp://{$resSFTP}/recon/$filename", 'w');

                if ($output === false) {
                    $return['data'] = array('code' => 505, 'message' => 'Unable to write file on remote server.', 'attachment' => null);
                } else {
                    foreach ($data as $row) {
                        fputcsv($output, $row);
                    }
                    fclose($output);
                    $return['data'] = array('code' => 200, 'message' => 'Recon file successfully uploaded with name : ' . $filename, 'attachment' => $filename);
                }
            } else {
                $return['data'] = array('code' => 500, 'message' => 'Unable to authenticate on server.', 'attachment' => null);
            }
        }        
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
    
    private function setMessage($key, $type, $customText = null)
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

}
