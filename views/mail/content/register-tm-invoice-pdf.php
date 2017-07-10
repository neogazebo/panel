<?php

use common\models\Currency;
?>
<div style="border-bottom: 1px solid #c1bebe;"><img style="width: 700px" src="<?= Yii::$app->urlManager->hostInfo ?>/img/header-invoice-small.jpg" /></div>
<div style="font-size: 34px;color: #f37021;padding: 25px 20px;">Tax Invoice</div>
<table style="width:100%;">
    <tr>
        <td style="text-align: left;vertical-align: top;padding: 0 20px;color: #828282;width:60%">
            <?= $data['model']->company->com_business_name ?><br />
            <?= $data['model']->company->com_address ?><br />
            <?= $data['model']->company->com_city ?><br />
            <?= $data['model']->company->com_postcode ?>
        </td>
        <td style="text-align: right;vertical-align: top;padding: 0 20px;color: #828282;width:40%">
            Tax Invoice No : <?= $data['invoice']->fsi_no ?><br />
            Date : <?= $data['invoice']->fsi_issue_date ?><br />
        </td>
    </tr>
</table>
<table style="padding: 10px 0;width:100%;">
    <thead style="color: #ec7323;">
    <tr>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">No</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Description</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Unit Cost</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Quantity</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Amount</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Tax Code</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">6% GST</th>
        <th style="text-align: left;border-bottom: 1px solid #c7c5c4;border-top: 2px solid #c7c5c4;padding: 18px;">Total</th>
    </tr>
    </thead>
    <?php $no = 1; $totalAmount=0;$currenySymbol=''; ?>
    <?php foreach ($data['model']->company->mySubscriptions as $row): ?>
        <?php
            $totalAmount = $totalAmount + ($row->featureSubscription->fes_price_before_tax + $row->featureSubscription->fes_gst_tax);
            $currenySymbol = Currency::symbol($row->fsc_payment_currency);
        ?>
        <tr>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= $no ?></td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= $row->featureSubscription->fes_name ?><br /><?= $row->featureSubscription->fes_description ?></td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_price_before_tax ?></td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;">1</td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_price_before_tax ?></td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;">SR</td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= $row->featureSubscription->fes_gst_tax ?></td>
            <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?= Currency::symbol($row->fsc_payment_currency) ?> <?= ($row->featureSubscription->fes_price_before_tax + $row->featureSubscription->fes_gst_tax) ?></td>            
        </tr>
        <?php $no++ ?>
    <?php endforeach; ?>
    <tr>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"></td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;">Total</td>
        <td style="text-align: left;border-bottom: 1px solid #c7c5c4;padding: 18px;"><?php echo $currenySymbol; ?> <?= $totalAmount; ?></td>
    </tr>
</table>
<table style="width: 95%;">

    <tr>
        <td valign="top" style="border-bottom: 1px solid #c7c5c4;">
            <div style="padding:20px">
                <div style="font-size: 18px; color: #ec7323; font-weight: bold;">Payment Terms</div>
                <div style="color: #828282;width:98%;">
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
        <td valign="top" class="bodyContent" style="border-bottom: 1px solid #c7c5c4;">
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
</table>
<table style="width:100%;">
    <tr>
        <td style="border-bottom: 1px solid #c7c5c4;width: 100%;">
            <div style="text-align: left;padding: 40px 20px;color: #828282;font-style: italic;">
                Copyright © <?= date('Y') ?> Ebizu Sdn. Bhd.
            </div>
        </td>
    </tr>
</table>