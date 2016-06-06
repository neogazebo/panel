<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body style="font:14px 'Myriad Pro';">
        <div style="width:800px;margin:auto;background-color:#ebecee;padding-top:20px;padding-bottom:40px"><div class="adM">
            </div><div style="text-align:center;margin-right:auto;margin-left:auto;width:450px;font-family:Arial;margin-bottom:20px;color:#999;font-style:italic;font-size:12px;line-height:1.3em">
                If you are having trouble viewing this email, please click here.
                To ensure delivery, please add <a href="mailto:noreply@ebizu.com" target="_blank">noreply@ebizu.com</a> to your address book.
            </div>
            <div style="width:600px;margin:auto;background-color:#fff;padding:25px 15px">
                <?= Yii::$app->controller->renderPartial($content, ['data' => $params]) ?>
            </div>
            <div class="adL">
            </div>
        </div>
    </body>
</html>
