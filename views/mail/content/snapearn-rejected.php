<!DOCTYPE html>
<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body style="background: #EEEEEE; margin: 0; padding: 0;" leftmargin="0" topmargin="0">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.25; color: #666; font-weight: 200;">
        <tbody>
            <tr>
                <td align="center">
                    <table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF">
                        <tbody>
                            <tr>
                                <td height="120" align="center">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td width="40%" align="right">Welcome</td>
                                                <td width="20%" align="center"><img width="78" height="64" alt="" src="http://ebizu.com/getmanis/email/img/headlogo.png"></td>
                                                <td width="40%" align="left"><a target="_blank" href="http://www.getmanis.com">www.getmanis.com</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr height="8" style="display: block;">
                                <td height="6">
                                    <img width="600" height="6" alt="" src="http://ebizu.com/getmanis/email/img/fushia.png">
                                </td>
                            </tr>
                            <tr bgcolor="#f2f2f2" style="font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.75; color: #666666; font-weight: 200; display: block; width: 600px;">
                                <td width="600" align="right">
                                    <table width="550" cellspacing="0" cellpadding="0" border="0">
                                        <?= Yii::$app->controller->renderPartial($content, ['params' => $params]) ?>
                                    </table>
                                </td>
                            </tr>
                            <tr height="8" style="display: block;">
                                <td height="6">
                                    <img width="600" height="6" alt="" src="http://ebizu.com/getmanis/email/img/fushia.png" />
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" style="font-size: 10px;">Copyright &copy; 2014 Ebizu Sdn Bhd, All rights reserved. 
                                    You are receiving this email because you opted in or signed up for services. 
                                    <br>To manage your e-mail subscriptions or remove yourself from our e-mail program, click here
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="center" style="font-size: 10px;">Be social, Check us out!<br>
                                    <a target="_blank" href="https://www.facebook.com/getmanis">
                                        <img width="27" height="27" alt="Like us on FB" src="http://ebizu.com/getmanis/email/img/fb.png">
                                    </a>&nbsp;&nbsp;
                                    <a target="_blank" href="http://twitter.com/getmanis">
                                        <img width="26" height="27" alt="Follow us on Twitter" src="http://ebizu.com/getmanis/email/img/twt.png">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
