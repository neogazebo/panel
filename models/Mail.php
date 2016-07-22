<?php

namespace app\models;

use Yii;
use HTML2PDF;
use Swift_Attachment;
use Aws\S3\S3Client;

// require_once Yii::$app->basePath . '/../common/lib/' . 'aws' . DIRECTORY_SEPARATOR . 'aws.phar';

class Mail
{
    CONST SUBJECT_EMPLOYEE_REGISTER = 'WELCOME TO EBIZU';
    CONST BODY_EMPLOYEE_REGISTER = '//mail/content/register-employee';
    CONST SUBJECT_REGISTER_TM_INVOICE = "INVOICE EBIZU SUBSCRIPTION";
    CONST BODY_REGISTER_TM_INVOICE = '//mail/content/register-tm-invoice';
    CONST BODY_REGISTER_INVOICE_GST = '//mail/content/register-invoice-gst';
    CONST SUBJECT_REGISTER_TM_RECEIPT = "RECEIPT EBIZU SUBSCRIPTION";
    CONST BODY_REGISTER_TM_RECEIPT = '//mail/content/register-tm-receipt';
    CONST BODY_REGISTER_RECEIPT_GST = '//mail/content/register-receipt-gst';
    CONST SUBJECT_WELCOME = "Thank You For Signing Up";
    CONST SUBJECT_ACTIVATION_TM = 'SurePay POS | Your application have been approved!';
    CONST SUBJECT_ACTIVATION_MANAGER = 'Ebizu Manager | Your application have been approved!';
    CONST SUBJECT_START_TM = 'SurePay POS | Get started';
    CONST SUBJECT_START_MANAGER = 'Ebizu Manager | Get started';
    CONST SUBJECT_SALES_SUMMARY_DAILY = 'Sales Summary - Daily';
    CONST SUBJECT_PROMO_CREATED = 'Promo Created by Merchant';
    CONST SUBJECT_MEMBER_ACTIVATION = 'MEMBER ACTIVATION';
    CONST BODY_WELCOME = '//mail/content/welcome';
    CONST BODY_WELCOME_EBC = '//mail/content/welcome-ebc';
    CONST BODY_MEMBER_ACTIVATION = '//mail/content/member-activation';
    CONST BODY_WELCOME_POS = '//mail/content/welcome-manager';
    CONST BODY_WELCOME_POS_TM = '//mail/content/welcome-tm';
    CONST BODY_ACTIVATION_POS_TM = '//mail/content/activation-tm';
    CONST BODY_ACTIVATION_MANAGER = '//mail/content/activation-manager';
    CONST BODY_START_POS_TM = '//mail/content/start-tm';
    CONST BODY_START_MANAGER = '//mail/content/start-manager';
    CONST BODY_POS_REPORT = '//mail/content/pos-report';
    CONST BODY_PROMO_CREATED = '//mail/content/promo-created-to-sales';
    CONST SUBJECT_EMPLOYEE_RESET_PASSWORD = 'RESET PIN EBIZU';
    CONST BODY_EMPLOYEE_RESET_PASSWORD = '//mail/content/reset-password-employee';
    CONST SUBJECT_REGISTER_TM_INVOICE_TEST = "INVOICE EBIZU SUBSCRIPTION";
    CONST BODY_REGISTER_TM_INVOICE_TEST = '//mail/content/register-tm-invoice-test';
    CONST SUBJECT_WELCOME_ADMIN = 'Youre registered to Ebizu Admin Centre';
    CONST BODY_WELCOME_ADMIN = '//mail/content/welcome';
    CONST SUBJECT_ACTIVAION_MASTER = 'Youre registered to Ebizu Admin Centre';
    CONST BODY_ACTIVAION_MASTER = '//mail/content/activation';
    CONST BODY_SNAPEARN_REJECTED = '//mail/content/snapearn-rejected';

    // RHB
    CONST SUBJECT_REGISTER_RHB_INVOICE = 'RECEIPT RHB SUBSCRIPTION';
    CONST BODY_REGISTER_INVOICE_RHB = '//mail/content/register-invoice-rhb';
    CONST SUBJECT_EMPLOYEE_REGISTER_RHB = 'WELCOME TO RHB';
    CONST BODY_EMPLOYEE_REGISTER_RHB = '//mail/content/register-employee-rhb';
    CONST BODY_REGISTER_RECEIPT_GST_RHB = '//mail/content/register-receipt-gst-rhb';
    CONST BODY_WELCOME_RHB = '//mail/content/welcome-rhb';
    CONST SUBJECT_TEST_SES = 'WELCOME TO RHB';
    CONST BODY_TEST_SES = '//mail/content/test-ses';

    CONST CC_INVOICE_GUYS = 'azizuan@ebizu.com';
    CONST BCC_INVOICE_GUYS = 'tajhul@ebizu.com';

    public $to;
    public $cc = '';
    public $bcc = '';
    public $subject;
    public $body;
    public $params = [];
    public $from = 'Ebizu Support <support@ebizu.com>';
    public $template = '//mail/template/template-pos';
    public $attach = false;
    public $attachParams = [];

    public function __construct($from, $to, $subject, $body, $params = [], $template = null, $attach = false, $attachParams)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->params = $params;
        $this->attach = $attach;
        $this->attachParams = $attachParams;
        $this->from = $from;
        if (!empty($template))
            $this->template = $template;

        $this->saveTemplateContentAsLog();
        return $this->sendMailWithCronJob();
    }

    public function sendMail()
    {
        $to = array($this->to);
        if (key_exists('invoice', $this->params) || key_exists('receipt', $this->params)) {
            $to[] = $this->cc;
            $to[] = $this->cc2;
        }
        $mail = Yii::$app->mail->compose($this->template, [
                'content' => $this->body,
                'params' => $this->params
            ])
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($this->subject);

        if ($this->attach) {
            $mail->attach($this->attachPdf($this->attachParams['path'], $this->attachParams['content']));
        }

        if ($mail->send()) {
            $this->saveTemplateContentAsLog();
            if (EmailQueue::logSave($this->from, $this->to, $this->subject, Yii::$app->controller->renderPartial($this->template, [
                'content' => $this->body,
                'params' => $this->params
            ])))
                return true;
        }
        return false;
    }

    public function sendMailWithCronJob()
    {
        $attachJson = '';
        if (key_exists('invoice', $this->params) || key_exists('receipt', $this->params)) {
            $this->cc = self::CC_INVOICE_GUYS;
            $this->bcc = self::BCC_INVOICE_GUYS;
            if ($this->attach) {
                $this->attachPdf($this->attachParams['path'], $this->attachParams['content']);
                $this->saveAttachemetToS3($this->attachParams['path'], $this->attachParams['download_path']);
                $attachJson = $this->jsonFormatAttachement($this->attachParams['download_path']);
            }
        } else {
            if ($this->attach && !empty($this->attachParams['attachment_url'])) {
                $contentType = 'application/pdf';
                $info = [
                    'filename' => $this->attachParams['filename'],
                    'path' => $this->attachParams['attachment_url'],
                    'contentType' => $contentType
                ];
                $attachJson = json_encode($info);
            }
        }        
        
        $mailBody = Yii::$app->controller->renderPartial($this->body, [
            'content' => $this->template,
            'params' => $this->params
        ]);

        if (EmailQueue::insertToSendingMail($this->from, $this->to, $this->cc, $this->bcc, $this->subject, $mailBody, $mailBody, $attachJson)) {
            return true;
        }
        return false;
    }

    protected function attachPdf($path, $content)
    {
        require_once(dirname(__FILE__) . '/../lib/html2pdf/html2pdf.class.php');
        try {
            $html2pdf = new HTML2PDF('P', 'A4', 'fr');
            $html2pdf->WriteHTML($content);
            $pdfdoc = $html2pdf->Output($path, 'F');
            return $path;
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    protected function saveTemplateContentAsLog()
    {
        if (key_exists('invoice', $this->params)) {
            $model = $this->params['invoice'];
            $model->fsi_content = Yii::$app->controller->renderPartial($this->template . '-pdf', ['content' => $this->body . '-pdf', 'params' => $this->params]);
            return $model->save();
        }
        if (key_exists('receipt', $this->params)) {
            $model = $this->params['receipt'];
            $model->fsr_content = Yii::$app->controller->renderPartial($this->template . '-pdf', ['content' => $this->body . '-pdf', 'params' => $this->params]);
            return $model->save();
        }
    }

    public static function send($from, $to, $subject, $body, $params = [], $template, $attach = false, $attachParams = [])
    {
        return new self($from, $to, $subject, $body, $params, $template, $attach, $attachParams);
    }

    public static function sendWithCronjob($from, $to, $subject, $body, $params = [], $template, $attach = false, $attachParams = [])
    {
        return new self($from, $to, $subject, $body, $params, $template, $attach, $attachParams);
    }

    public static function jsonFormatAttachement($fileName)
    {
        $awsUrl = 'https://d1307f5mo71yg9.cloudfront.net/files/attachments/invoice/';
        $contentType = 'application/pdf';
        $fileInfo = [];
        $info = ['filename' => $fileName, 'path' => $awsUrl . $fileName, 'contentType' => $contentType];
        return json_encode($info);
    }

    public static function saveAttachemetToS3($localPath, $fileName)
    {
        $s3Path = 'files/attachments/invoice/';
        $currentPath = Yii::$app->getBasePath();
        $bucket = Yii::$app->params['s3bucket'];
        $client = S3Client::factory(array(
            'key' => Yii::$app->params['s3key'],
            'secret' => Yii::$app->params['s3secret'],
                'region' => Yii::$app->params['s3region']
        ));
        try {
            $result = $client->putObject(array(
                'Bucket' => $bucket,
                'Key' => $s3Path . $fileName,
                'CacheControl' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                'SourceFile' => $localPath,
                'ACL' => 'public-read',
            ));
        } catch (S3Exception $e) {
            var_dump($e);
            exit;
        }

        if (file_exists($localPath . $fileName))
            unlink($localPath . $fileName);
    }

}
