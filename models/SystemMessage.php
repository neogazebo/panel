<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_system_message".
 *
 * @property integer $sym_id
 * @property integer $sym_type
 * @property string $sym_name
 * @property string $sym_title
 * @property string $sym_message
 * @property integer $sym_priority
 * @property string $sym_language
 */
class SystemMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_system_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sym_type', 'sym_name', 'sym_message'], 'required'],
            [['sym_type', 'sym_priority'], 'integer'],
            [['sym_message'], 'string'],
            [['sym_name', 'sym_title'], 'string', 'max' => 300],
            [['sym_language'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sym_id' => 'Sym ID',
            'sym_type' => 'Sym Type',
            'sym_name' => 'Sym Name',
            'sym_title' => 'Sym Title',
            'sym_message' => 'Sym Message',
            'sym_priority' => 'Sym Priority',
            'sym_language' => 'Sym Language',
        ];
    }

    /**
     * @inheritdoc
     * @return SystemMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SystemMessageQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->sym_name = mb_convert_encoding($this->sym_name, 'UTF-8');
            $this->sym_message = mb_convert_encoding($this->sym_message, 'UTF-8');
            return true;
        }
        return false;
    }

    public function parser($id=0, $name, $to, $parsers, $language="en_us", $from_name="Ebizu", $use_logo=false)
    {
        if($id > 0) {
            $model = SystemMessage::findOne($id);
            $title = $model->sym_title;
            $message = $model->sym_message;
        }

        foreach($parsers as $row) {
            $message = str_replace($row[0], $row[1], $message);
        }

        $logo = '';
        if ($use_logo)
            $logo = '<img src="'.Yii::$app->params['businessUrl'].$use_logo.'" width="90px" height="60px"/>';
        else
            $logo = '<img src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" width="121px" height="32px"/>';
        $com_id = $parsers[0][1];
        // if($com_id)
        //     $company_name = Company::findOne($com_id)->com_name;
        // else
            $company_name = 'Ebizu';
        if($name == 'welcome_message') {
            $messages_plain = '
                Congratulations!\r\n\r\n
                Welcome to Ebizu. You have taken the first step to embrace the future of small business management and marketing. It\'s mobile, social and in the cloud.\r\n
                We have strived to create a great solution for you and will continue to do so. Get started by logging into our Business Centre and start learning using our guides and help section. Good luck and have fun transforming your business!\r\n\r\n
                Get started quickly by logging into your Business Centre below. You can then choose to learn more about our solution by reading our guides and taking our tour. Ideally, you want to complete the following steps upon signing up.\r\n
                1. Complete your business profile\r\n
                2. Import your customers and contacts\r\n
                3. Create a campaign\r\n
                4. Install your required apps\r\n
                5. Promote yourself using various channels\r\n\r\n
                <a http="'.Yii::$app->params['frontendUrl'].'site/login">CLick here to login to business centre</a>
                ';
            $title = 'Welcome, '.$company_name;

            // html body
            $body ='
            <div style="width: 800px; margin: auto; background-color: #ebecee; padding-top: 20px; padding-bottom: 40px;">
                <div style="text-align:center; margin-right:auto; margin-left:auto; width:450px; font-family:Arial;margin-bottom: 20px; color: #999; font-style: italic; font-size: 12px; line-height: 1.3em">
                        If you are having trouble viewing this email, please click here.
                        To ensure delivery, please add noreply@ebizu.com to your address book.
                </div>
                <div style="width: 600px; margin: auto; background-color: #fff; padding: 25px 15px;">
                    <table style="width:100%;">
                        <td style="width:50%; text-align:left;">
                             '.$logo.'
                        </td>
                        <td style="width:50%; text-align:right;">
                            <p style="font-family:Arial; font-size:16px;color:#006ab8;font-weight: bold; margin-left: 10px;">'.$title.'</p>
                        </td>
                    </table>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">
                            <table>
                                <tr>
                                <td>
                                    <p style="font-size:36px;">Congratulations!</p><br/>
                                    <p style="line-height:1.3em;">Welcome to Ebizu. You have taken the first step to embrace the future of small business management and marketing. It\'s mobile, social and in the cloud.</p><br/>
                                    <p style="line-height:1.3em;">We have strived to create a great solution for you and will continue to do so. Get started by logging into our Business Centre and start learning using our guides and help section. Good luck and have fun transforming your business!</p><br/>
                                    <p><img src="'.Yii::$app->params['imageUrl'].'ebizu_signature.png"></p>
                                </td>
                                <td style="width:250px;">
                                    <img src="'.Yii::$app->params['imageUrl'].'business_welcome_email.png" style="margin-left:-38px; margin-bottom: -18px;">
                                </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">
                            <p style="font-size:24px; color:#006ab8;">quick start guide</p><br/>
                            <p style="line-height:1.3em;">Get started quickly by logging into your Business Centre below. You can then choose to learn more about our solution by reading our guides and taking our tour. Ideally, you want to complete the following steps upon signing up.</p><br/>
                            <table>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'get_started_1.png" /></td>
                                <td style="padding-left:10px; font-size:14px;">Complete your business profile</td>
                            </tr>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'get_started_2.png" /></td>
                                <td style="padding-left:10px; font-size:14px;">Import your customers and contacts</td>
                            </tr>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'get_started_3.png" /></td>
                                <td style="padding-left:10px; font-size:14px;">Create a campaign</td>
                            </tr>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'get_started_4.png" /></td>
                                <td style="padding-left:10px; font-size:14px;">Install your required apps</td>
                            </tr>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'get_started_5.png" /></td>
                                <td style="padding-left:10px; font-size:14px;">Promote yourself using various channels</td>
                            </tr>
                            </table><br/>
                            <center>
                                <a href="'.Yii::$app->params['frontendUrl'].'site/login"><img src="'.Yii::$app->params['imageUrl'].'login_to_business_centre_button.jpg" /></a>
                            </center>
                        </div>
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">
                            <p style="font-size:24px; color:#006ab8;">download our apps</p><br/>
                            <p style="line-height:1.3em; margin:0px;">Download the required Ebizu Manager app from the app stores links below. This app will allow your customers to easily check-in to your business while being able to redeem offers and collect loyalty points.</p><br/>
                            <table>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'icon_appstore.png" width="139" height="60"></td>
                                <td style="padding-left:15px;"><img src="'.Yii::$app->params['imageUrl'].'icon_googleplay.png" width="139" height="60"></td>
                                <td style="padding-left:15px;">Windows Phone and Blackberry versions coming soon!</td>
                            </tr>
                            </table>
                        </div>
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; padding: 20px 10px 20px 10px; line-height: 1.3em;">
                        You are receiving this email because you registered on ebizu.com with this email address.
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <center>
                        <table border=0 cellpadding=0 cellspacing=0 style="margin-top:30px;">
                            <tr>
                                <td valign="middle" style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">
                                    Powered by
                                </td>
                                <td valign="middle" style="padding-left:10px;">
                                    <img style="width:100px;" src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" />
                                </td>
                            </tr>
                        </table>
                    </center>
                </div>
            </div>';
            $messages = $body;
        } else if ($name=='email_confirm_customer'){
            $mem_id = $parsers[2][1];
            $com_id = $parsers[3][1];
            $messages_plain = '
                Dear Valued Customer\r\n\r\n
                You have received this email because '.$company_name.' has recently upgraded it\'s system to serve you better! We are now part of the growing Ebizu network, where you can enjoy exclusive offers and rewards from merchants around the city.\r\n\r\n
                Click the link below to continue to get exclusive content from us and interact with us using the Ebizu app which is available below.\r\n\r\n
                <a href="'.Yii::$app->params['frontendUrl'].'site/login">YES! COUNT ME IN. I WANT EXCLUSIVE STUFF!</a>\r\n
                <a href="'.Yii::$app->params['frontendUrl'].'site/login">no thanks</a>
            ';

            $title = 'Welcome!';

            //Html body
            $body = '
            <div style="width: 800px; margin: auto; background-color: #ebecee; padding-top: 20px; padding-bottom: 40px;">
                <div style="text-align:center; margin-right:auto; margin-left:auto; width:450px; font-family:Arial;margin-bottom: 20px; color: #999; font-style: italic; font-size: 12px; line-height: 1.3em">
                    If you are having trouble viewing this email, please click here.
                    To ensure delivery, please add noreply@ebizu.com to your address book.
                </div>
                <div style="width: 566px; margin: auto; background-color: #fff; padding: 25px 15px;">
                    <table style="width:100%;">
                        <td style="width:50%; text-align:left;">
                             '.$logo.'
                        </td>
                        <td style="width:50%; text-align:right;">
                            <p style="font-family:Arial; font-size:16px;color:#006ab8;font-weight: bold; margin-left: 10px;">'.$title.'</p>
                        </td>
                    </table>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px;">
                            <table>
                                <tr valign="top">
                                <td valign="top">
                                    <p style="font-size:21px; color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">Dear Valued Customer</p><br/>
                                    <p style="line-height:1.3em; font-size:12px;color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">You have received this email because '.$company_name.' has recently upgraded it\'s system to serve you better! We are now part of the growing Ebizu network, where you can enjoy exclusive offers and rewards from merchants around the city.</p><br/>
                                    <p style="line-height:1.3em; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">Click the button below to continue to get exclusive content from us and interact with us using the Ebizu app which is available below.</p>
                                </td>
                                <td valign="top">
                                    <img src="'.Yii::$app->params->businessURL.Company::model()->findByPk(base64_decode($com_id))->com_banner_photo.'" style="margin-left:15px; width:280px;" />
                                </td>
                                </tr>
                            </table><br/>
                            <center>
                                <div style="margin-bottom:10px;"><a href="'.Yii::$app->params['frontendUrl'].'customer/subscribe?a='.$mem_id.'&b='.$com_id.'"><img src="'.Yii::$app->params['imageUrl'].'count_me_in_button.jpg" alt="Count Me In!"/></a></div>
                                <div><a href="'.Yii::$app->params['frontendUrl'].'customer/unsubscribe?a='.$mem_id.'&b='.$com_id.'" style="text-decoration:none;font-size:10px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">No thanks</a></div>
                            </center>
                        </div>
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">
                            <p style="font-size:24px; color:#006ab8; margin-top: 0px; margin-bottom: 0px;">download the app</p><br/>
                            <p style="line-height:1.3em;">Download the Ebizu app to get access to exclusive offers, rewards, and events from your favourite merchants. You can also view and interact with their content and enjoy special treatment.</p><br/>
                            <table>
                            <tr>
                                <td><img src="'.Yii::$app->params['imageUrl'].'icon_appstore.png" width="139" height="60"></td>
                                <td style="padding-left:15px;"><img src="'.Yii::$app->params['imageUrl'].'icon_googleplay.png" width="139" height="60"></td>
                                <td style="padding-left:15px;font-size:24px; color:#006ab8;">Get the App now!</td>
                            </tr>
                            </table>
                        </div>
                    </div>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; padding: 20px 10px 20px 10px; line-height: 1.3em;">
                        You are receiving this email because '.$company_name.' has added you as their customer. If you are not their customer and wish to stop receiving this email, please click here to unsubscribe or you can modify which emails to receive under your account settings page.
                    </div>
                    <div style="padding: 20px 10px 20px 10px;">
                        <a href="'.Yii::$app->params['frontendUrl'].'business/about" style="margin-right: 20px; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; text-decoration:none;">About Ebizu</a>
                        <a href="'.Yii::$app->params['frontendUrl'].'site/toc" style="margin-right: 20px; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; text-decoration:none;">Terms & Conditions</a>
                        <a href="'.Yii::$app->params['frontendUrl'].'site/privacy" style="margin-right: 20px; font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; text-decoration:none;">Privacy Policy</a>
                    </div><br/>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <center>
                        <table border=0 cellpadding=0 cellspacing=0 style="margin-top:30px;">
                            <tr>
                                <td valign="middle" style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">
                                    Powered by
                                </td>
                                <td valign="middle" style="padding-left:10px;">
                                    <img style="width:100px;" src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" />
                                </td>
                            </tr>
                        </table>
                    </center>
                </div>
            </div>';
            $messages = $body;
        } elseif($name == 'bug_solved' || $name == 'bug_verified' || $name == 'new_bug' || $name == 'new_task') {
            $messages_plain = $message;

            //Html body
            $body ='<div style="width: 800px; margin: auto; background-color: #ebecee; padding-top: 20px; padding-bottom: 40px;">
                    <div style="text-align:center; margin-right:auto; margin-left:auto; width:450px; font-family:Arial;margin-bottom: 20px; color: #999; font-style: italic; font-size: 12px; line-height: 1.3em">
                            If you are having trouble viewing this email, please click here.
                            To ensure delivery, please add noreply@ebizu.com to your address book.
                    </div>
                    <div style="width: 600px; margin: auto; background-color: #fff; padding: 25px 15px;">
                        <table style="width:100%;">
                            <td style="width:50%; text-align:left;">
                                 '.$logo.'
                            </td>
                            <td style="width:50%; text-align:right;">
                                <p style="font-family:Arial; font-size:16px;color:#006ab8;font-weight: bold; margin-left: 10px;">Bug Report</p>
                            </td>
                        </table>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <div style="padding: 20px 10px 15px 10px;">
                            <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">'.$message.'</div>
                        </div>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; padding: 20px 10px 20px 10px; line-height: 1.3em;">
                            You are receiving this email because you registered on admin.ebizu.com with this email address.
                        </div>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <center>
                            <table border=0 cellpadding=0 cellspacing=0 style="margin-top:30px;">
                                <tr>
                                    <td valign="middle" style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">
                                        Powered by
                                    </td>
                                    <td valign="middle" style="padding-left:10px;">
                                        <img style="width:100px;" src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" />
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </div>
                </div>
                ';
            $messages = $body;
        } elseif($name == 'snapearn_receipt_blur' || $name == 'snapearn_receipt_dark' || $name == 'snapearn_receipt_incomplete' || $name == 'snapearn_receipt_suspicious' || $name == 'snapearn_duplicate' || $name == 'snapearn_invalid' || $name = 'snapearn_receipt_violates') {
            $messages_plain = $message;
            $body = '
                <!DOCTYPE>
                <html>
                <head>
                  <META http-equiv="Content-Type" content="text/html; charset=utf-8">
                  </head>
                  <body style="background: #eeeeee; margin:0;padding:0;" leftmargin="0" topmargin="0">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:14px; line-height:1.25;color:#666;font-weight:200;">
                      <tbody><tr>
                        <td align="center">
                          <table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF">
                            <tbody><tr>
                              <td height="120" align="center"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tbody>
                                 <tr>
                                   <td width="40%" align="right">Welcome</td>
                                   <td width="20%" align="center"><img width="78" height="64" alt="" src="http://ebizu.com/getmanis/email/img/headlogo.png"></td>
                                   <td width="40%" align="left">www.getmanis.com</td>
                                 </tr>
                               </tbody>
                             </table></td>
                           </tr>
                           <tr height="8" style="display:block;">
                            <td height="6"><img width="600" height="6" alt="" src="http://ebizu.com/getmanis/email/img/fushia.png"></td>
                          </tr>
                          <tr bgcolor="#f2f2f2" style="font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:14px; line-height:1.75;color:#666;font-weight:200;display:block; width:600px;">
                            <td width="600" align="right"><table width="550" cellspacing="0" cellpadding="0" border="0">'.$message.'</table></td>
                        </tr>
                        <tr height="8" style="display:block;">
                          <td height="6"><img width="600" height="6" alt="" src="http://ebizu.com/getmanis/email/img/fushia.png"></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="center" style="font-size:10px;">Copyright &copy; 2014 Ebizu Sdn Bhd, All rights reserved.
                            You are receiving this email because you opted in or signed up for services.
                            <br>To manage your e-mail subscriptions or remove yourself from our e-mail program, click here</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center" style="font-size:10px;">Be social, Check us out!<br><a target="_blank" href="https://www.facebook.com/getmanis"><img width="27" height="27" alt="Like us on FB" src="http://ebizu.com/getmanis/email/img/fb.png"></a>&nbsp;&nbsp;<a target="_blank" href="http://twitter.com/getmanis"><img width="26" height="27" alt="Follow us on Twitter" src="http://ebizu.com/getmanis/email/img/twt.png"></a></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>

                </body></html>
            ';
            $messages = $body;
        } elseif($name == 'prolong') {
            $messages_plain = $message;
            $body = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <!-- NAME: 1 COLUMN -->
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <style type="text/css">
                            /* /\/\/\/\/\/\/\/\/ CLIENT-SPECIFIC STYLES /\/\/\/\/\/\/\/\/ */
                            #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                            body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
                            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                            img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                            /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
                            body{margin:0; padding:0;}
                            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
                            table{border-collapse:collapse !important;}
                            body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;}
                            /* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */
                            /* ========== Page Styles ========== */
                            .strong {
                                font-weight: bold;
                            }
                            .spacing {
                                padding: 0 10px;
                            }
                            .bodyContent {
                                background-color: #ffffff;
                            }
                            #titleContent {
                                padding: 20px;
                                background-color: #ffffff;
                            }
                            .invoiceTable {
                                border: 1px solid #CCCCCC;
                            }
                            .headerTableRow {
                                font-weight: bold;
                            }
                            .headerTableRow td {
                                background-color: #dee0e2;
                            }
                            .text-right {
                                text-align: right;
                            }
                            #bodyCell{padding:20px;}
                            #templateContainer{width:600px;}
                            /**
                            * @tab Page
                            * @section background style
                            * @tip Set the background color and top border for your email. You may want to choose colors that match your companys branding.
                            * @theme page
                            */
                            body, #bodyTable{
                            /*@editable*/ background-color:#DEE0E2;
                            }
                            /**
                            * @tab Page
                            * @section background style
                            * @tip Set the background color and top border for your email. You may want to choose colors that match your companys branding.
                            * @theme page
                            */
                            #bodyCell{
                            /*@editable*/ border-top:4px solid #BBBBBB;
                            }
                            /**
                            * @tab Page
                            * @section email border
                            * @tip Set the border for your email.
                            */
                            #templateContainer{
                            /*@editable*/ border:1px solid #BBBBBB;
                            }
                            /**
                            * @tab Page
                            * @section heading 1
                            * @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
                            * @style heading 1
                            */
                            h1{
                            /*@editable*/ color: #595f69 !important;
                            display:block;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:26px;
                            /*@editable*/ font-style:normal;
                            /*@editable*/ font-weight:bold;
                            /*@editable*/ line-height:100%;
                            /*@editable*/ letter-spacing:normal;
                            margin-top:0;
                            margin-right:0;
                            margin-bottom:10px;
                            margin-left:0;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Page
                            * @section heading 2
                            * @tip Set the styling for all second-level headings in your emails.
                            * @style heading 2
                            */
                            h2{
                            /*@editable*/ color:#595f69 !important;
                            display:block;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:20px;
                            /*@editable*/ font-style:normal;
                            /*@editable*/ font-weight:bold;
                            /*@editable*/ line-height:100%;
                            /*@editable*/ letter-spacing:normal;
                            margin-top:0;
                            margin-right:0;
                            margin-bottom:10px;
                            margin-left:0;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Page
                            * @section heading 3
                            * @tip Set the styling for all third-level headings in your emails.
                            * @style heading 3
                            */
                            h3{
                            /*@editable*/ color:#595f69 !important;
                            display:block;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:16px;
                            /*@editable*/ font-style:italic;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ line-height:100%;
                            /*@editable*/ letter-spacing:normal;
                            margin-top:0;
                            margin-right:0;
                            margin-bottom:10px;
                            margin-left:0;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Page
                            * @section heading 4
                            * @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
                            * @style heading 4
                            */
                            h4{
                            /*@editable*/ color:#808080 !important;
                            display:block;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:14px;
                            /*@editable*/ font-style:italic;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ line-height:100%;
                            /*@editable*/ letter-spacing:normal;
                            margin-top:0;
                            margin-right:0;
                            margin-bottom:10px;
                            margin-left:0;
                            /*@editable*/ text-align:left;
                            }
                            /* ========== Header Styles ========== */
                            /**
                            * @tab Header
                            * @section preheader style
                            * @tip Set the background color and bottom border for your emails preheader area.
                            * @theme header
                            */
                            #templatePreheader{
                            /*@editable*/ background-color: #595f69;
                            /*@editable*/ border-bottom:1px solid #CCCCCC;
                            }
                            /**
                            * @tab Header
                            * @section preheader text
                            * @tip Set the styling for your emails preheader text. Choose a size and color that is easy to read.
                            */
                            .preheaderContent{
                            /*@editable*/ color:#ffffff;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:10px;
                            /*@editable*/ line-height:125%;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Header
                            * @section preheader link
                            * @tip Set the styling for your emails preheader links. Choose a color that helps them stand out from your text.
                            */
                            .preheaderContent a:link, .preheaderContent a:visited, /* Yahoo! Mail Override */ .preheaderContent a .yshortcuts /* Yahoo! Mail Override */{
                            /*@editable*/ color:#ffffff;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            /**
                            * @tab Header
                            * @section header style
                            * @tip Set the background color and borders for your emails header area.
                            * @theme header
                            */
                            #templateHeader{
                            /*@editable*/ background-color:#ffffff;
                            /*@editable*/ border-top:1px solid #FFFFFF;
                            /*@editable*/ border-bottom:1px solid #CCCCCC;
                            }
                            /**
                            * @tab Header
                            * @section header text
                            * @tip Set the styling for your emails header text. Choose a size and color that is easy to read.
                            */
                            .headerContent{
                            /*@editable*/ color:#505050;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:20px;
                            /*@editable*/ font-weight:bold;
                            /*@editable*/ line-height:100%;
                            /*@editable*/ padding-top:20px;
                            /*@editable*/ padding-right:20px;
                            /*@editable*/ padding-bottom:20px;
                            /*@editable*/ padding-left:20px;
                            /*@editable*/ text-align:left;
                            /*@editable*/ vertical-align:middle;
                            }
                            #title {
                                padding-top: 10px;
                                text-align: center;
                            }
                            /**
                            * @tab Header
                            * @section header link
                            * @tip Set the styling for your emails header links. Choose a color that helps them stand out from your text.
                            */
                            .headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
                            /*@editable*/ color:#EB4102;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            #headerImage{
                            height:auto;
                            max-width:600px;
                            }
                            /* ========== Body Styles ========== */
                            /**
                            * @tab Body
                            * @section body style
                            * @tip Set the background color and borders for your emails body area.
                            */
                            #templateBody{
                            /*@editable*/ background-color:#ffffff;
                            /*@editable*/ border-top:1px solid #FFFFFF;
                            /*@editable*/ border-bottom:1px solid #CCCCCC;
                            }
                            /**
                            * @tab Body
                            * @section body text
                            * @tip Set the styling for your emails main content text. Choose a size and color that is easy to read.
                            * @theme main
                            */
                            .bodyContent{
                            /*@editable*/ color:#505050;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:14px;
                            /*@editable*/ line-height:150%;
                            padding-top:20px;
                            padding-right:20px;
                            padding-bottom:20px;
                            padding-left:20px;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Body
                            * @section body link
                            * @tip Set the styling for your emails main content links. Choose a color that helps them stand out from your text.
                            */
                            .bodyContent a:link, .bodyContent a:visited, /* Yahoo! Mail Override */ .bodyContent a .yshortcuts /* Yahoo! Mail Override */{
                            /*@editable*/ color:#4d90d4;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            .bodyContent img{
                            display:inline;
                            height:auto;
                            max-width:560px;
                            }
                            /* ========== Column Styles ========== */
                            .templateColumnContainer{width:260px;}
                            /**
                            * @tab Columns
                            * @section column style
                            * @tip Set the background color and borders for your emails column area.
                            */
                            #templateColumns{
                            /*@editable*/ background-color:#ffffff;
                            /*@editable*/ border-top:1px solid #FFFFFF;
                            /*@editable*/ border-bottom:1px solid #CCCCCC;
                            }
                            /**
                            * @tab Columns
                            * @section left column text
                            * @tip Set the styling for your emails left column content text. Choose a size and color that is easy to read.
                            */
                            .leftColumnContent{
                            /*@editable*/ color:#505050;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:14px;
                            /*@editable*/ line-height:150%;
                            padding-top:0;
                            padding-right:20px;
                            padding-bottom:20px;
                            padding-left:20px;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Columns
                            * @section left column link
                            * @tip Set the styling for your emails left column content links. Choose a color that helps them stand out from your text.
                            */
                            .leftColumnContent a:link, .leftColumnContent a:visited, /* Yahoo! Mail Override */ .leftColumnContent a .yshortcuts /* Yahoo! Mail Override */{
                            /*@editable*/ color:#EB4102;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            /**
                            * @tab Columns
                            * @section right column text
                            * @tip Set the styling for your emails right column content text. Choose a size and color that is easy to read.
                            */
                            .rightColumnContent{
                            /*@editable*/ color:#505050;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:14px;
                            /*@editable*/ line-height:150%;
                            padding-top:0;
                            padding-right:20px;
                            padding-bottom:20px;
                            padding-left:20px;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Columns
                            * @section right column link
                            * @tip Set the styling for your emails right column content links. Choose a color that helps them stand out from your text.
                            */
                            .rightColumnContent a:link, .rightColumnContent a:visited, /* Yahoo! Mail Override */ .rightColumnContent a .yshortcuts /* Yahoo! Mail Override */{
                            /*@editable*/ color:#EB4102;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            .leftColumnContent img, .rightColumnContent img{
                            display:inline;
                            height:auto;
                            max-width:260px;
                            }
                            /* ========== Footer Styles ========== */
                            /**
                            * @tab Footer
                            * @section footer style
                            * @tip Set the background color and borders for your emails footer area.
                            * @theme footer
                            */
                            #templateFooter{
                            /*@editable*/ border-top:1px solid #FFFFFF;
                            }
                            /**
                            * @tab Footer
                            * @section footer text
                            * @tip Set the styling for your emails footer text. Choose a size and color that is easy to read.
                            * @theme footer
                            */
                            .footerContent{
                            /*@editable*/ color:#ffffff;
                            /*@editable*/ font-family:Helvetica;
                            /*@editable*/ font-size:10px;
                            /*@editable*/ line-height:150%;
                            padding-top:20px;
                            padding-right:20px;
                            padding-bottom:0;
                            padding-left:20px;
                            /*@editable*/ text-align:left;
                            }
                            /**
                            * @tab Footer
                            * @section footer link
                            * @tip Set the styling for your emails footer links. Choose a color that helps them stand out from your text.
                            */
                            .footerContent a:link, .footerContent a:visited, /* Yahoo! Mail Override */ .footerContent a .yshortcuts, .footerContent a span /* Yahoo! Mail Override */{
                            /*@editable*/ color:#606060;
                            /*@editable*/ font-weight:normal;
                            /*@editable*/ text-decoration:underline;
                            }
                            /* /\/\/\/\/\/\/\/\/ MOBILE STYLES /\/\/\/\/\/\/\/\/ */
                            @media only screen and (max-width: 480px){
                            /* /\/\/\/\/\/\/ CLIENT-SPECIFIC MOBILE STYLES /\/\/\/\/\/\/ */
                            body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:none !important;} /* Prevent Webkit platforms from changing default text sizes */
                            body{width:100% !important; min-width:100% !important;} /* Prevent iOS Mail from adding padding to the body */
                            /* /\/\/\/\/\/\/ MOBILE RESET STYLES /\/\/\/\/\/\/ */
                            #bodyCell{padding:10px !important;}
                            /* /\/\/\/\/\/\/ MOBILE TEMPLATE STYLES /\/\/\/\/\/\/ */
                            /* ======== Page Styles ======== */
                            /**
                            * @tab Mobile Styles
                            * @section template width
                            * @tip Make the template fluid for portrait or landscape view adaptability. If a fluid layout doesnt work for you, set the width to 300px instead.
                            */
                            #templateContainer{
                                max-width:600px !important;
                                /*@editable*/ width:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section heading 1
                            * @tip Make the first-level headings larger in size for better readability on small screens.
                            */
                            h1{
                                /*@editable*/ font-size:24px !important;
                                /*@editable*/ line-height:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section heading 2
                            * @tip Make the second-level headings larger in size for better readability on small screens.
                            */
                            h2{
                                /*@editable*/ font-size:20px !important;
                                /*@editable*/ line-height:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section heading 3
                            * @tip Make the third-level headings larger in size for better readability on small screens.
                            */
                            h3{
                                /*@editable*/ font-size:18px !important;
                                /*@editable*/ line-height:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section heading 4
                            * @tip Make the fourth-level headings larger in size for better readability on small screens.
                            */
                            h4{
                                /*@editable*/ font-size:16px !important;
                                /*@editable*/ line-height:100% !important;
                            }
                            /* ======== Header Styles ======== */
                            #templatePreheader{display:none !important;} /* Hide the template preheader to save space */
                            /**
                            * @tab Mobile Styles
                            * @section header image
                            * @tip Make the main header image fluid for portrait or landscape view adaptability, and set the images original width as the max-width. If a fluid setting doesnt work, set the image width to half its original size instead.
                            */
                            #headerImage{
                                height:auto !important;
                                /*@editable*/ max-width:600px !important;
                                /*@editable*/ width:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section header text
                            * @tip Make the header content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                            */
                            .headerContent{
                                /*@editable*/ font-size:20px !important;
                                /*@editable*/ line-height:125% !important;
                            }
                            /* ======== Body Styles ======== */
                            /**
                            * @tab Mobile Styles
                            * @section body text
                            * @tip Make the body content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                            */
                            .bodyContent{
                                /*@editable*/ font-size:18px !important;
                                /*@editable*/ line-height:125% !important;
                            }
                            /* ======== Column Styles ======== */
                            .templateColumnContainer{display:block !important; width:100% !important;}
                            /**
                            * @tab Mobile Styles
                            * @section column image
                            * @tip Make the column image fluid for portrait or landscape view adaptability, and set the images original width as the max-width. If a fluid setting doesnt work, set the image width to half its original size instead.
                            */
                            .columnImage{
                                height:auto !important;
                                /*@editable*/ max-width:480px !important;
                                /*@editable*/ width:100% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section left column text
                            * @tip Make the left column content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                            */
                            .leftColumnContent{
                                /*@editable*/ font-size:16px !important;
                                /*@editable*/ line-height:125% !important;
                            }
                            /**
                            * @tab Mobile Styles
                            * @section right column text
                            * @tip Make the right column content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                            */
                            .rightColumnContent{
                                /*@editable*/ font-size:16px !important;
                                /*@editable*/ line-height:125% !important;
                            }
                            /* ======== Footer Styles ======== */
                            /**
                            * @tab Mobile Styles
                            * @section footer text
                            * @tip Make the body content text larger in size for better readability on small screens.
                            */
                            .footerContent{
                                /*@editable*/ font-size:14px !important;
                                /*@editable*/ line-height:115% !important;
                            }
                            .footerContent a{display:block !important;} /* Place footer social and utility links on their own lines, for easier access */
                            }
                        </style>
                    </head>
                    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                        <center>
                            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                                            <tr>
                                                <td align="center" valign="top" id="templateHeader">
                                                    <!-- Begin Template Header -->
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td class="headerContent">
                                                                <img src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" style="max-width:600px;" id="headerImage campaign-icon" mc:label="header_image" mc:edit="header_image" mc:allowdesigner mc:allowtext>
                                                            </td>
                                                            <td style="padding-right: 20px; text-align: right">
                                                                <p><em>Transform your business</em></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- End Template Header -->
                                                </td>
                                            </tr>'.$message.'
                                            <tr>
                                                <td valign="top" class="bodyContent">
                                                    <table border="1" cellpadding="10" cellspacing="0" width="100%" class="invoiceTable">
                                                        <tr class="headerTableRow">
                                                            <td style="text-align: center">No</td>
                                                            <td style="text-align: center">Description</td>
                                                            <td style="text-align: center">Qty</td>
                                                            <td style="text-align: center">Currency</td>
                                                            <td style="text-align: center">Unit Price</td>
                                                            <td style="text-align: center">Sub Total</td>
                                                        </tr>
            ';
            $total = 0;
            $num = 1;
            $start = strtotime(date('d m Y'));
            for($i = 0; $i < $parsers[5][1]; $i++) {
                $end = strtotime('+1 month', $start);
                $body .= '
                                                        <tr>
                                                            <td style="text-align: right">'.$num.'</td>
                                                            <td>'.$parsers[0][1].' Manis Subscription '.date('d M Y', $start).' - '.date('d M Y', $end).'</td>
                                                            <td style="text-align: right">1</td>
                                                            <td style="text-align: center">'.$parsers[6][1].'</td>
                                                            <td style="text-align: right">'.$parsers[7][1].'</td>
                                                            <td style="text-align: right">'.$parsers[7][1].'</td>
                                                        </tr>
                ';
                $start = $end;
                $total += $parsers[7][1];
                $num++;
            }
            $body .= '
                                                        <tr>
                                                            <td colspan="5" class="strong" style="text-align: right">Grand Total</td>
                                                            <td class="strong" style="text-align: right">'.$total.'</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                    <td align="center" valign="top">
                                        <!-- Begin Template Footer -->
                                        <!-- BEGIN FOOTER -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter" style="background: #488dcb">
                                            <tr>
                                                <td valign="top" class="footerContent" style="color: #fff; padding: 20px;">
                                                    <p>
                                                        <a href="http://www.facebook.com/ebizudotcom" target="_blank" style="padding-right: 5px; text-decoration: none;">
                                                            <img src="http://ebizu.com/getmanis/email/img/fb.png" width="27" height="27" alt="Like us on FB" />
                                                        </a>
                                                        <a href="https://twitter.com/ebizudotcom" target="_blank" style="text-decoration: none;">
                                                            <img src="http://ebizu.com/getmanis/email/img/twt.png" width="26" height="27" alt="Follow us on Twitter" />
                                                        </a>
                                                    </p>
                                                    <p>
                                                        You are receiving this email because you registered on <a href="http://ebizu.com" style="color: #fff">ebizu.com</a> with this email address.
                                                    </p>
                                                    <p>
                                                        <em>Copyright &copy; 2014 Ebizu Sdn. Bhd., All rights reserved.</em>
                                                    </p>

                                                    <p>
                                                        <strong>Our mailing address is:</strong><br />Ebizu Sdn. Bhd., Suite 2-2, Level 2, Tower 9, Avenue 5, The Horizon, Bangsar South, No.8 Jalan Kerinchi, 5900 Kuala Lumpur
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- END FOOTER -->
                                        <!-- End Template Footer -->
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </body>
                </html>
            ';
            $messages = $body;
        } else if ($name=='email_reset_password'){
            $title                  = 'Link Reset Password';
            $company_name           = $parsers[0][1];
            $link                   = $parsers[1][1];
            $title_messages_plain   = 'Hi, '.$company_name;
            $messages_plain         = 'Please follow this '.$link.' to reset your password, this link is valid for next 2 hours';
            //Html body
            $body = '
            <div style="width: 800px; margin: auto; background-color: #ebecee; padding-top: 20px; padding-bottom: 40px;">
                <div style="text-align:center; margin-right:auto; margin-left:auto; width:450px; font-family:Arial;margin-bottom: 20px; color: #999; font-style: italic; font-size: 12px; line-height: 1.3em">
                    If you are having trouble viewing this email, please click here.
                    To ensure delivery, please add noreply@ebizu.com to your address book.
                </div>
                <div style="width: 566px; margin: auto; background-color: #fff; padding: 25px 15px;">
                    <table style="width:100%;">
                        <td style="width:50%; text-align:left;">
                             '.$logo.'
                        </td>
                        <td style="width:50%; text-align:right;">
                            <p style="font-family:Arial; font-size:16px;color:#006ab8;font-weight: bold; margin-left: 10px;">'.$title.'</p>
                        </td>
                    </table>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <div style="padding: 20px 10px 15px 10px;">
                        <div style="font-size:12px;">
                            <table>
                                <tr valign="top">
                                    <td valign="top" colspan="2">
                                        <p style="font-size:21px; color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">'.$title_messages_plain.'</p><br/>
                                        <p style="line-height:1.3em; font-size:12px;color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">'.$messages_plain.'</p><br/>
                                        <p style="line-height:1.3em; font-size:10px; color:#999; font-family:Arial, Helvetica, sans-serif; margin:0px;">You are receiving this email because you registered on ebizu.com with this email address.</p>
                                    </td>
                                </tr>
                            </table><br/>
                            <center>
                            </center>
                        </div>
                    </div>
                    <br/>
                    <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                    <center>
                        <table border=0 cellpadding=0 cellspacing=0 style="margin-top:30px;">
                            <tr>
                                <td valign="middle" style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">
                                    Powered by
                                </td>
                                <td valign="middle" style="padding-left:10px;">
                                    <img style="width:100px;" src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" />
                                </td>
                            </tr>
                        </table>
                    </center>
                </div>
            </div>';
            $messages = $body;
        } elseif($name == 'pos_signup') {
            $body = '
                <html>
                    <head></head>
                    <body style="font:12px Arial;">
                        <div style="width:1000px;margin:auto;background-color:#ebecee;padding: 20px 0 40px">
                            <div style="text-align:center;margin-right:auto;margin-left:auto;width:450px;font-family:Arial;margin-bottom:20px;color:#999;font-style:italic;font-size:12px;line-height:1.3em">
                                If you are having trouble viewing this email, please click here.
                                To ensure delivery, please add <a href="mailto:noreply@ebizu.com" target="_blank">noreply@ebizu.com</a> to your address book.
                            </div>
                            <div style="width:800px;margin:auto;background-color:#fff;padding:30px 30px">
                                <table>
                                    <tr>
                                        <td><div style="border-bottom: 1px solid #c1bebe"><img width="800px" src="'.Yii::$app->urlManager->hostInfo.'/img/header-invoice.jpg" /></div></td>
                                    </tr>
                                    <tr>
                                        <td id="titleContent">
                                            <div style="font-size: 34px;color: #f37021;padding: 25px 20px;">Thank you for registering your business with Surepay POS!</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="color: #828282; padding:20px;">
                                                '.$message.'
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">
                                            <div style="padding:20px">
                                                <div style="color: #828282;font-style: italic;">
                                                    <p>
                                                        You are receiving this email because you registered on <a href="http://ebizu.com">ebizu.com</a> with this email address.
                                                        If you wish to stop receiving this email, please click here to unsubscribe or you can modify which emails to receive under your account settings page
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">
                                            <div style="text-align: left;padding: 40px 20px; width: 50%; float: left;color: #828282;font-style: italic;">
                                                Copyright © '.date('Y').' Ebizu Sdn. Bhd.
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </body>
                </html>
            ';
            $messages = $body;
        } elseif($name == 'pos_activation') {
            $header_image   = Yii::$app->urlManager->hostInfo.'/img/header-invoice.jpg';
            $color          = '#f37021';
            if(!empty($parsers[3][1])){

                if($parsers[3][1] == 'RHB'){
                    $header_image   = Yii::$app->urlManager->hostInfo.'/img/header-invoice-rhb.jpg';
                    $color          = '#6ccee6';
                }
            }
            $body = '
                <html>
                    <head></head>
                    <body style="font:12px Arial;">
                        <div style="width:1000px;margin:auto;background-color:#ebecee;padding: 20px 0 40px">
                            <div style="text-align:center;margin-right:auto;margin-left:auto;width:450px;font-family:Arial;margin-bottom:20px;color:#999;font-style:italic;font-size:12px;line-height:1.3em">
                                If you are having trouble viewing this email, please click here.
                                To ensure delivery, please add <a href="mailto:noreply@ebizu.com" target="_blank">noreply@ebizu.com</a> to your address book.
                            </div>
                            <div style="width:800px;margin:auto;background-color:#fff;padding:30px 30px">
                                <table>
                                    <tr>
                                        <td><div style="border-bottom: 1px solid #c1bebe"><img width="800px" src="'.$header_image.'" /></div></td>
                                    </tr>
                                    <tr>
                                        <td id="titleContent">
                                            <div style="font-size: 34px;color: '.$color.';padding: 25px 20px;">Thank You!</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="color: #828282; padding:20px;">
                                                '.$message.'
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">
                                            <div style="padding:20px">
                                                <div style="color: #828282;font-style: italic;">
                                                    <p>
                                                        You are receiving this email because you registered on <a href="http://ebizu.com">ebizu.com</a> with this email address.
                                                        If you wish to stop receiving this email, please click here to unsubscribe or you can modify which emails to receive under your account settings page
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">
                                            <div style="text-align: left;padding: 40px 20px; width: 50%; float: left;color: #828282;font-style: italic;">
                                                Copyright © '.date('Y').' Ebizu Sdn. Bhd.
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </body>
                </html>
            ';
            $messages = $body;
        } else {
            $messages_plain = $message;

            //Html body
            $body ='<div style="width: 800px; margin: auto; background-color: #ebecee; padding-top: 20px; padding-bottom: 40px;">
                    <div style="text-align:center; margin-right:auto; margin-left:auto; width:450px; font-family:Arial;margin-bottom: 20px; color: #999; font-style: italic; font-size: 12px; line-height: 1.3em">
                            If you are having trouble viewing this email, please click here.
                            To ensure delivery, please add noreply@ebizu.com to your address book.
                    </div>
                    <div style="width: 600px; margin: auto; background-color: #fff; padding: 25px 15px;">
                        <table style="width:100%;">
                            <td style="width:50%; text-align:left;">
                                 '.$logo.'
                            </td>
                            <td style="width:50%; text-align:right;">
                                <p style="font-family:Arial; font-size:16px;color:#006ab8;font-weight: bold; margin-left: 10px;">'.$title.'</p>
                            </td>
                        </table>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <div style="padding: 20px 10px 15px 10px;">
                            <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif;">'.$message.'</div>
                        </div>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <div style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic; padding: 20px 10px 20px 10px; line-height: 1.3em;">
                            You are receiving this email because you registered on ebizu.com with this email address.
                        </div>
                        <div style="clear: both; border-bottom: solid 1px #e4e4e4;"></div>
                        <center>
                            <table border=0 cellpadding=0 cellspacing=0 style="margin-top:30px;">
                                <tr>
                                    <td valign="middle" style="font-size:12px; color:#999; font-family:Arial, Helvetica, sans-serif; font-style: italic;">
                                        Powered by
                                    </td>
                                    <td valign="middle" style="padding-left:10px;">
                                        <img style="width:100px;" src="'.Yii::$app->params['imageUrl'].'ebizu_logo.png" />
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </div>
                </div>
                ';
            $messages = $body;
        }

        // send with aws ses
        // require_once Yii::$app->params['libPath'].'aws'.DIRECTORY_SEPARATOR.'aws-autoloader.php';
        // $client = SesClient::factory([
        //     'key'    => Yii::$app->params['s3key'],
        //     'secret' => Yii::$app->params['s3secret'],
        //     'region' => Yii::$app->params['s3SESregion']
        // ]);

        // $result = $client->sendEmail([
        //     'Source' => Yii::$app->params['adminEmail'],
        //     'Destination' => [
        //         'ToAddresses' => [$to],
        //     ],
        //     'Message' => [
        //         'Subject' => [
        //             'Data' => $title,
        //             'Charset' => 'UTF-8',
        //         ],
        //         'Body' => [
        //             'Text' => [
        //                 'Data' => $messages_plain,
        //                 'Charset' => 'UTF-8',
        //             ],
        //             'Html' => [
        //                 'Data' => $messages,
        //                 'Charset' => 'UTF-8',
        //             ],
        //         ],
        //     ],
        //     'ReplyToAddresses' => [Yii::$app->params['adminEmail']],
        // ]);
        // echo 'adminEmail : '.Yii::$app->params['adminEmail'].'<br/>';
        // echo 'To : ' .$to.'<br/>';
        // echo 'Title : ' .$title.'<br/>';
        // echo 'Messages : ' .$messages.'<br/>';
        // echo 'Messages Plain : ' .$messages_plain.'<br/>';
        // die;
        $this->insertQueue(Yii::$app->params['adminEmail'],$to,$title,$messages,$messages_plain='',null);
        // $kmail_id   = $this->insertQueue(Yii::$app->params['adminEmail'],$time,$to,$title,$messages,$messages_plain,null);

        // if(!empty($result['MessageId'])){
        //     $this->updateQueue($kmail_id,$result['MessageId']);
        // }
    }

    static function insertQueue($from,$to,$subject,$body,$body_plain,$additional_headers=array(),$priority=5)
    {
        $connection = Yii::$app->db;
        /*if(!$connection) throw new Exception('Database connection not found');
        if(!is_string($to) and !is_array($to)) throw new Exception('$to can only be a string or an array');
        */
        $to = json_encode($to);
        $insertSql = 'INSERT INTO `kemail_queue` (`priority`, `time`, `from`, `to`, `subject`, `body`, `body_plain`)
            VALUES (:priority, DATE_ADD(NOW(), INTERVAL 1 MINUTE), :from, :to, :subject, :body, :body_plain)';
        $command = $connection->createCommand($insertSql);
        $command->bindParam(':priority', $priority);
        $command->bindParam(':from', $from);
        $command->bindParam(':to', $to);
        $command->bindParam(':subject', $subject);
        $command->bindParam(':body', $body);
        $command->bindParam(':body_plain', $body_plain);
        $command->execute();
        // return $connection->getLastInsertID();
    }

    static function updateQueue($queue_id,$aws_message_id)
    {
        $connection = Yii::$app->db;
        if(!$connection) throw new Exception('Database connection not found');
        $insertSql = "update kemail_queue set aws_message_id ='".$aws_message_id."', sent=now() where id ='".$queue_id."'";
        $command = $connection->createCommand($insertSql);
        return $command->execute();
    }
}
