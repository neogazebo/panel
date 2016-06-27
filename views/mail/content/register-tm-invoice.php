<?php

use common\models\Currency;
?>
<table>
    <tr>
        <td><div style="border-bottom: 1px solid #c1bebe"><img width="1000px" src="<?= Yii::$app->urlManager->hostInfo ?>/img/header-invoice.jpg" /></div></td>
    </tr>
    <tr>
        <td id="titleContent">
            <div style="font-size: 34px;color: #f37021;padding: 25px 20px;">Tax Invoice</div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="text-align: left;vertical-align: top;padding: 0 20px; width: 50%; float: left;color: #828282;">
                <?= $data['model']->company->com_business_name ?><br />
                <?= $data['model']->company->com_address ?><br /> 
                <?= $data['model']->company->com_city ?><br /> 
                <?= $data['model']->company->com_postcode ?>
            </div>
            <div style="text-align: right;vertical-align: top;padding: 0 20px; width: 41%; float: left;color: #828282;">
                Tax Invoice No : <?= $data['invoice']->fsi_no ?><br />
                Date : <?= $data['invoice']->fsi_issue_date ?><br /> 
            </div>
        </td>
    </tr>
    <tr>
        <td valign="top" class="bodyContent">
            <table border="0" cellpadding="10" cellspacing="0" width="100%" style="padding: 30px 0;">
                <thead style="color: #ec7323;">
                    <tr>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">No</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;width:200px;">Description</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">Unit Cost</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">Quantity</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">Amount</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">Tax Code</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">6% GST</th>
                        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 20px;">Total</th>
                    </tr>
                </thead>
                <?php $no = 1; $totalAmount=0;$currenySymbol=''; ?>
                <?php foreach ($data['model']->company->mySubscriptions as $row): ?>
                    <?php
                        $totalAmount = $totalAmount + ($row->featureSubscription->fes_price_before_tax + $row->featureSubscription->fes_gst_tax);
                        $currenySymbol = Currency::symbol($row->fsc_payment_currency);
                    ?>
                    <tr>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= $no ?></td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= $row->featureSubscription->fes_name ?><br /><?= $row->featureSubscription->fes_description ?></td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_price_before_tax ?></td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;">1</td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_price_before_tax ?></td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;">SR</td>
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_gst_tax ?></td>
                        
                        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 20px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= ($row->featureSubscription->fes_price_before_tax + $row->featureSubscription->fes_gst_tax) ?></td>
                    </tr>
                    <?php $no++ ?>
                <?php endforeach; ?>
                <tr>    
                    <td colspan="6" class="strong" style="border-bottom: 1px solid #c7c5c4;text-align: left"></td>
                    <td class="strong" style="border-bottom: 1px solid #c7c5c4;padding: 20px;text-align: left;font-size: 18px;font-weight: bold;color: #ec7323;">Total</td>
                    <td class="strong" style="border-bottom: 1px solid #c7c5c4;padding: 20px;text-align: left;font-size: 18px;font-weight: bold;"><?php echo $currenySymbol; ?> <?=$totalAmount;?></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td valign="top" style="border-bottom: 1px solid #CCCCCC">     
            <div style="padding:20px">
                <div style="font-size: 18px; color: #ec7323; font-weight: bold;">Payment Terms</div>
                <div style="color: #828282">
                    <p>If you have selected to pay via cheque or cash deposit, please deposit the amount above into our account as stated below and send your deposit slip to finance@ebizu.com</p>
                    <p>
                        Ebizu Sdn. Bhd. <br />
                        CIMB Bank <br />
                        Account No: 8602153013
                    </p>
                    <p>
                        Please make the deposit within 7 days of receiving this invoice or your account will be suspended.<br />
                        If you have made payment and are having problems accessing your account, please contact us at <br />support@ebizu.com
                    </p>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">     
            <div style="padding:20px">
                <div style="color: #828282;font-style: italic;">
                    <p>
                        You are receiving this email because you registered on ebizu.com with this email address<br />
                        If you wish to stop receiving this email, please click here to unsubscribe or you can modify<br />
                        which emails to receive under your account settings page.
                    </p>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #CCCCCC">     
            <div style="text-align: left;padding: 40px 20px; width: 50%; float: left;color: #828282;font-style: italic;">
                Copyright © <?= date('Y') ?> Ebizu Sdn. Bhd. 
            </div>
        </td>
    </tr>
</table>