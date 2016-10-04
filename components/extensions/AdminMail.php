<?php
namespace app\components\extensions;

use Yii;
use yii\base\Component;
use app\models\Mail;
use app\models\Voucher;
use HTML2PDF;

class AdminMail extends Component
{
    public $template = false;

    public function init()
    {
        return parent::init();
    }

    public function backend($to, $params = [])
    {
        return new TBackend($this->template, $to, $params);
    }
}

class TMail
{
    public $template = '//mail/template/template';
    public $body;
    public $params = [];
    public $to = [];
    public $from = 'Ebizu <support@ebizu.com>';
    public $subject;
    public $attach = false;
    public $attachParams = [
        'path' => null,
        'content' => null
    ];
    
    public function __construct($template, $to, $params = [])
    {
        $this->to = $to;
        $this->params = $params;
        $this->template = $template;
    }

    public function send()
    {
        Mail::send($this->from, $this->to, $this->subject, $this->body, $this->params, $this->template, $this->attach, $this->attachParams);
        return $this;
    }

    public function view()
    {
        return Yii::$app->controller->renderPartial($this->body, ['content' => $this->template, 'params' => $this->params]);
    }
}

class TBackend extends TMail
{
    public function welcome()
    {
        $this->template = '//mail/template/template';
        $this->subject = Mail::SUBJECT_WELCOME_ADMIN;
        $this->body = Mail::BODY_WELCOME_ADMIN;
        return $this;
    }

    public function memberActivation()
    {
        $this->template = '//mail/template/template';
        $this->subject = Mail::SUBJECT_MEMBER_ACTIVATION;
        $this->body = Mail::BODY_MEMBER_ACTIVATION;
        return $this;
    }

    public function registerEmployee()
    {
        $this->template = '//mail/template/template-pos';
        $this->subject = Mail::SUBJECT_EMPLOYEE_REGISTER;
        $this->body = Mail::BODY_EMPLOYEE_REGISTER;
        return $this;
    }

    public function stockVoucher($subject)
    {
        $this->template = '//mail/template/template';
        $this->subject = $subject;
        $this->body = Mail::BODY_STOCK_LEFT;
        return $this;
    }

    public function snapearnRejected($type)
    {
        switch ($type) {
            case 1:
                $this->template = '//mail/template/snapearn/blurry';
                $this->subject = 'Receipt photo is blurry';
                break;
            case 2:
                $this->template = '//mail/template/snapearn/dark';
                $this->subject = 'Receipt photo is too dark';
                break;
            case 3:
                $this->template = '//mail/template/snapearn/incomplete';
                $this->subject = 'Receipt photo is incomplete';
                break;
            case 4:
                $this->template = '//mail/template/snapearn/suspicious';
                $this->subject = 'Claim under investigation';
                break;
            case 5:
                $this->template = '//mail/template/snapearn/duplicate';
                $this->subject = 'Receipt Already Uploaded';
                break;
            case 6:
                $this->template = '//mail/template/snapearn/invalid';
                $this->subject = 'Invalid Receipt Uploaded';
                break;
            case 7:
                $this->template = '//mail/template/snapearn/violates';
                $this->subject = 'Receipt violates Manis T&Cs';
                break;
            case 8:
                $this->template = '//mail/template/snapearn/invalid';
                $this->subject = 'Invalid Merchant Location';
                break;
            case 9:
                $this->template = '//mail/template/snapearn/invalid';
                $this->subject = 'Receipt uploaded more than 24 hours after transaction time';
                break;
            case 10:
                $this->template = '//mail/template/snapearn/violates';
                $this->subject = 'More than 2 receipts uploaded from this outlet today';
                break;
        }
        $this->body = Mail::BODY_SNAPEARN_REJECTED;
        return $this;
    }
    
    public function resetPasswordEmployee()
    {
        $this->template = '//mail/template/template-pos';
        $this->subject = Mail::SUBJECT_EMPLOYEE_RESET_PASSWORD;
        $this->body = Mail::BODY_EMPLOYEE_RESET_PASSWORD;
        return $this;
    }

    public function registerTmInvoice()
    {
        $this->from = 'Ebizu Finance <finance@ebizu.com>';
        $this->template = '//mail/template/template-invoice';
        $this->subject = Mail::SUBJECT_REGISTER_TM_INVOICE;
        $this->body = Mail::BODY_REGISTER_TM_INVOICE;
        $this->attach = true;
        $this->attachParams = [
            'path' => $this->getAttachPath($this->params['invoice']->fsi_no),
            'content' => $this->getAttachContent()
        ];
        return $this;
    }
    
    public function registerTmReceipt()
    {
        $this->from = 'Ebizu Finance <finance@ebizu.com>';
        $this->template = '//mail/template/template-invoice';
        $this->subject = Mail::SUBJECT_REGISTER_TM_RECEIPT;
        $this->body = Mail::BODY_REGISTER_TM_RECEIPT;
        $this->attach = true;
        $this->attachParams = [
            'path' => $this->getAttachPath($this->params['receipt']->fsr_no),
            'content' => $this->getAttachContent()
        ];
        return $this;
    }
    
    protected function getAttachContent()
    {
        return Yii::$app->controller->renderPartial($this->template . '-pdf', ['content' => $this->body . '-pdf', 'params' => $this->params]);
    }
    
    protected function getAttachPath($no)
    {
        return Yii::$app->getRuntimePath() . '/' . str_replace('/', '-', $no) . '.pdf';
    }
    
}
