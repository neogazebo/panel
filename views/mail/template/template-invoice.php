<html>
    <head></head>
    <body style="font:12px 'Arial';">
        <div style="width:1080px;margin:auto;background-color:#ebecee;padding: 20px 0 40px">
            <div style="text-align:center;margin-right:auto;margin-left:auto;width:450px;font-family:Arial;margin-bottom:20px;color:#999;font-style:italic;font-size:12px;line-height:1.3em">
                If you are having trouble viewing this email, please click here.
                To ensure delivery, please add <a href="mailto:noreply@ebizu.com" target="_blank">noreply@ebizu.com</a> to your address book.
            </div>
            <div style="width:1000px;margin:auto;background-color:#fff;padding:30px 30px">
                <?php echo Yii::$app->controller->renderPartial($content, ['data' => $params]) ?>
            </div>
        </div>
    </body>
</html>