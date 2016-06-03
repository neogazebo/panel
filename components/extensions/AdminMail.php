<?php

namespace common\components\extentions;

use Yii;
use yii\base\Component;
use common\models\Mail;
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
        return Yii::$app->controller->renderPartial($this->template, ['content' => $this->body, 'params' => $this->params]);
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
    
    public function resetPasswordEmployee()
    {
        $this->template = '//mail/template/template-pos';
        $this->subject = Mail::SUBJECT_EMPLOYEE_RESET_PASSWORD;
        $this->body = Mail::BODY_EMPLOYEE_RESET_PASSWORD;
        return $this;
    }

    public function registerTmInvoice()
    {
        $this->from = 'finance@ebizu.com';
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
        $this->from = 'finance@ebizu.com';
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
