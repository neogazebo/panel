<?php
use common\models\Currency;
use common\models\User;
?>

<table style="width: 100%;border-bottom: 1px solid #ccc;font: 12px 'Arial'">
    <tr>
        <th class="banner" style="text-align: left;vertical-align: middle; padding: 3px 18px;">
            <?php if($data['model']->company->com_registered_to == User::REGISTERED_TO_TM):?>
                <img style="width: 100%;height: 70px;margin-left: -10px;" src="<?= Yii::$app->urlManager->hostInfo ?>/img/header-invoice-rhb.jpg" />
            <?php else:?>
                <img style="width: 100%;margin-left: -10px;" src="<?= Yii::$app->urlManager->hostInfo ?>/img/header-invoice-mgr.jpg" />
            <?php endif;?>
        </th>
    </tr>
</table>
<br/>
<table style="width: 100%;font: 12px 'Arial';line-height: 20px;">
    <tr>
        <td style="width: 30%;padding: 10px;text-align: left;vertical-align: top;">
            <strong>Ebizu Sdn. Bhd. (1039440-P)</strong><br/><br/>
            Level 8, Ikhlas Point Tower 11, Avenue 5,<br/>
            The Horizon Bangsar South,<br/>
            No. 8 Jalan Kerinchi, 59200, KL.<br/>
            03-22422667  finance@ebizu.com<br/>
            <strong>GST Registration No : 002074411008</strong>
        </td>
        <td style="width: 15%;padding: 10px;"></td>
        <td style="width: 30%;padding: 10px;text-align: right;vertical-align: top;">
            <strong>RECEIPT</strong><br/><br/>
            RECEIPT NO. : <?= $data['receipt']->fsr_no ?> <br/>
            DATE : <?= date('d-M-Y', strtotime($data['receipt']->fsr_issue_date)) ?><br/>
            BILLING PERIODE : 
            <?=date('d-M-Y',$data['model']->company->mySubscription->fsc_valid_start)?>
            <span> - </span>
            <?=date('d-M-Y',$data['model']->company->mySubscription->fsc_valid_end)?>
        </td>
        <!--<td style="width: 5%;padding: 10px;"></td>-->
    </tr>
</table>

<table style="width: 100%;font: 12px 'Arial';line-height: 20px;">
    <tr>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 2%;"></td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 2%">TO</td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 2%;"></td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 34%">
            <?= $data['model']->company->com_business_name ?><br/>
            <?= $data['model']->company->com_address ?><br/>
            <?= $data['model']->company->com_postcode ?> <?= $data['model']->company->com_city ?><br/>
            <br/>
        </td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 30%;"></td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 5%">ATTENTION</td>
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;width: 10%">
            <?= $data['model']->company->com_name ?><br/>
            <?= $data['model']->company->com_phone ?><br/>
            <span style="color: #0000EE;"><u><?= $data['model']->company->com_email ?></u></span><br/>
        </td>        
        <td style="padding: 10px;line-height: 20px;text-align: left;vertical-align: top;"></td>
    </tr>
</table>
<br/>
<br/>
<br/>

<table style="width: 100%;font: 12px 'Arial';line-height: 20px;" cellspacing="0" cellpadding="0">
    <tr style="color: #000;background: #ccc;">
        <th style="padding: 5px;width: 5%;">NO.</th>
        <th style="padding: 5px;width: 30%">DESCRIPTION</th>
        <th style="padding: 5px;width: 15%;">TOTAL (EXCL. GST)</th>
        <th style="padding: 5px;width: 15%;">6% GST</th>
        <th style="padding: 5px;width: 15%;">TOTAL (INCL. GST)</th>
        <th style="padding: 5px;width: 10%;">TAX CODE</th>
    </tr>
    
    <?php 
    $mySubscription = $data['model']->company->mySubscription;
    ?>
    
    <?php if(count($mySubscription->featureSubscription->detailPackage) > 0):?>
        <?php 
            $totalTax = 0;
            $totalPrice = 0;
            $totalPriceBeforeTax = 0;
       ?>
        <?php foreach($mySubscription->featureSubscription->detailPackage as $i=>$mySubscriptionDetail):?>
        <?php 
            $totalPriceBeforeTax = $totalPriceBeforeTax + $mySubscriptionDetail->fsi_price_before_tax;
            $totalTax = $totalTax + $mySubscriptionDetail->fsi_gst_tax;
            $totalPrice = $totalPrice + ($mySubscriptionDetail->fsi_price_before_tax + $mySubscriptionDetail->fsi_gst_tax);
        
            $priceBeforeTax = number_format(round($mySubscriptionDetail->fsi_price_before_tax, 2), 2);
            $taxAmount = number_format(round($mySubscriptionDetail->fsi_gst_tax, 2), 2);
            $price = number_format(round(($mySubscriptionDetail->fsi_price_before_tax + $mySubscriptionDetail->fsi_gst_tax), 2), 2);
        ?>
  
        <tr>
            <td style="padding: 5px;text-align: center;border-left: 1px solid #ccc;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;"><?=$i+1?></td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <?= $mySubscription->featureSubscription->fes_name ?> - <?=$mySubscriptionDetail->fsi_description?>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $priceBeforeTax ?></span>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $taxAmount ?></span>
            </td>
            <td style="padding: 5px;text-align: left;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $price ?></span>
            </td>
            <td style="padding: 5px;text-align: center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
                SR
            </td>                    
        </tr>    

        <?php endforeach;?>
        
        <tr>
            <td colspan="2" style="padding: 5px;text-align: center;"></td>
            <td style="padding: 5px;text-align: right;border-right: 1px solid #ccc;">
                <strong>TOTAL GST @ 6%</strong>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><strong><?= $mySubscription->fsc_payment_currency ?></strong></span> 
                <span style="float: right;"><strong><?= number_format(round($totalTax, 2), 2) ?></strong></span>
            </td>
            <td style="padding: 5px;text-align: left;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"></td>        
        </tr>    

        <tr>
            <td colspan="2" style="padding: 5px;text-align: center;"></td>
            <td colspan="2" style="padding: 5px;text-align: right;border-right: 1px solid #ccc;">
                <strong>GRAND TOTAL AMOUNT (INCL. GST)</strong>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;background: #ccc;color: #000;">
                <span style="float: left"><strong><?= $mySubscription->fsc_payment_currency ?></strong></span> 
                <span style="float: right;"><strong><?= number_format(round($totalPrice, 2), 2) ?></strong></span>
            </td>
        </tr>            
    <?php else:?>
    
        <?php 
            $priceBeforeTax = number_format(round($mySubscription->featureSubscription->fes_price_before_tax, 2), 2);
            $taxAmount = number_format(round($mySubscription->featureSubscription->fes_gst_tax, 2), 2);
            $totalPrice = number_format(round(($mySubscription->featureSubscription->fes_price_before_tax + $mySubscription->featureSubscription->fes_gst_tax), 2), 2);
        ?>
  
        <tr>
            <td style="padding: 5px;text-align: center;border-left: 1px solid #ccc;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">1</td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <?= $mySubscription->featureSubscription->fes_name ?>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $priceBeforeTax ?></span>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $taxAmount ?></span>
            </td>
            <td style="padding: 5px;text-align: left;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
                <span style="float: left"><?= $mySubscription->fsc_payment_currency ?></span> 
                <span style="float: right;"><?= $totalPrice ?></span>
            </td>
            <td style="padding: 5px;text-align: center;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
                SR
            </td>                    
        </tr>    

        <tr>
            <td colspan="2" style="padding: 5px;text-align: center;"></td>
            <td style="padding: 5px;text-align: right;border-right: 1px solid #ccc;">
                <strong>TOTAL GST @ 6%</strong>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                <span style="float: left"><strong><?= $mySubscription->fsc_payment_currency ?></strong></span> 
                <span style="float: right;"><strong><?= $taxAmount ?></strong></span>
            </td>
            <td style="padding: 5px;text-align: left;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;"></td>        
        </tr>    

        <tr>
            <td colspan="2" style="padding: 5px;text-align: center;"></td>
            <td colspan="2" style="padding: 5px;text-align: right;border-right: 1px solid #ccc;">
                <strong>GRAND TOTAL AMOUNT (INCL. GST)</strong>
            </td>
            <td style="padding: 5px;text-align: left;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;background: #ccc;color: #000;">
                <span style="float: left"><strong><?= $mySubscription->fsc_payment_currency ?></strong></span> 
                <span style="float: right;"><strong><?= $totalPrice ?></strong></span>
            </td>
        </tr>    
    <?php endif;?>      
    
</table>
<br/><br/><br/><br/>
<table style="width: 100%;font: 12px 'Arial';line-height: 20px;color:#474145" cellspacing="0" cellpadding="0">
    <tr>
        <td style="">Thank you for your payment. If you have problems accessing your account, please contact us at 
        <span style="color: #0000EE;"><u>support@ebizu.com</u></span>
        </td>
    </tr>
    <tr>
        <td style="padding: 25px 0;font-style: italic;font-size: 13px;">Copyright &copy; <?=date('Y')?> Ebizu Sdn. Bhd.</td>
    </tr>
    <tr><td>&nbsp;</td></tr>    
    <tr>
        <td style="text-align: center;font-style: italic;">
             This is a computer generated receipt, no signature is required.						
        </td>
    </tr>
</table>