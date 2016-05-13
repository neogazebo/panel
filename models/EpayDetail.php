<?php

namespace app\models;

/**
 * This is the model class for table "tbl_epay_detail".
 *
 * @property string $epd_id
 * @property integer $epd_epa_id
 * @property string $epd_request
 * @property integer $epd_amount
 * @property string $epd_merchant_id
 * @property string $epd_operator_id
 * @property string $epd_org_trans_ref
 * @property string $epd_ret_trans_ref
 * @property string $epd_terminal_id
 * @property string $epd_product_code
 * @property string $epd_msisdn
 * @property string $epd_trans_datetime
 * @property integer $epd_trans_trace_id
 * @property string $epd_custom_field_1
 * @property string $epd_custom_field_2
 * @property string $epd_custom_field_3
 * @property string $epd_custom_field_4
 * @property string $epd_custom_field_5
 * @property string $epd_macing
 * @property string $epd_pin
 * @property string $epd_pin_expiry_date
 * @property integer $epd_response_code
 * @property string $epd_response_msg
 * @property string $epd_trans_ref
 */
class EpayDetail extends \yii\db\ActiveRecord {
    const CLIENT_SHORTNAME = 'EBZ';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_epay_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['epd_epa_id', 'epd_amount', 'epd_trans_trace_id', 'epd_response_code'], 'integer'],
            [['epd_request', 'epd_response_msg'], 'string', 'max' => 100],
            [['epd_merchant_id'], 'string', 'max' => 15],
            [['epd_operator_id', 'epd_pin_expiry_date'], 'string', 'max' => 20],
            [['epd_org_trans_ref', 'epd_ret_trans_ref', 'epd_trans_ref'], 'string', 'max' => 40],
            [['epd_terminal_id'], 'string', 'max' => 8],
            [['epd_product_code', 'epd_msisdn'], 'string', 'max' => 30],
            [['epd_trans_datetime'], 'string', 'max' => 14],
            [['epd_custom_field_1', 'epd_custom_field_2', 'epd_custom_field_3', 'epd_custom_field_4', 'epd_custom_field_5'], 'string', 'max' => 1000],
            [['epd_macing'], 'string', 'max' => 128],
            [['epd_pin'], 'string', 'max' => 24]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'epd_id' => 'Epd ID',
            'epd_epa_id' => 'Epd Epa ID',
            'epd_request' => 'Epd Request',
            'epd_amount' => 'in cents',
            'epd_merchant_id' => 'provided by epay',
            'epd_operator_id' => 'Epd Operator ID',
            'epd_org_trans_ref' => 'Epd Org Trans Ref',
            'epd_ret_trans_ref' => 'Epd Ret Trans Ref',
            'epd_terminal_id' => 'Epd Terminal ID',
            'epd_product_code' => 'Epd Product Code',
            'epd_msisdn' => 'Epd Msisdn',
            'epd_trans_datetime' => 'yyyyMMddHHiiss',
            'epd_trans_trace_id' => 'Epd Trans Trace ID',
            'epd_custom_field_1' => 'Epd Custom Field 1',
            'epd_custom_field_2' => 'Epd Custom Field 2',
            'epd_custom_field_3' => 'Epd Custom Field 3',
            'epd_custom_field_4' => 'Epd Custom Field 4',
            'epd_custom_field_5' => 'Epd Custom Field 5',
            'epd_macing' => 'Epd Macing',
            'epd_pin' => 'Epd Pin',
            'epd_pin_expiry_date' => 'yyMMdd',
            'epd_response_code' => 'Epd Response Code',
            'epd_response_msg' => 'Epd Response Msg',
            'epd_trans_ref' => 'Epd Trans Ref',
        ];
    }

    public function getReconciliationData($recap = 'today', $date=null) {
        
        $stringdate = date('Ymd', (strtotime('-1 day', strtotime(date('Ymd')))));
        $epay_detail = EpayDetail::find()->where('epd_response_code="00"');
        if ($recap == 'today') {
            $stringdate = date('Ymd', (strtotime('-1 day', strtotime(date('Ymd')))));
        } else if ($recap == 'specific') {
            $stringdate = $date;
        }
        $epay_detail->andWhere('SUBSTR(epd_trans_datetime, 1, 8)= '.$stringdate);
        // echo $epay_detail->createCommand()->sql;exit;
        $model = $epay_detail->all();
        $rows = array();
        $total_detail = 0;
        $total_amount = 0;
        $last_client_trans = "";
        foreach ($model as $row) {
            $request_type = ($row->epd_request == 'ONLINE PIN' || $row->epd_request == 'PIN' || $row->epd_request == 'PIN-REV') ? 'PIN' : 'ETU';
            $amount_in_RM = $row->epd_amount / 100;
            $formated_amount = number_format(round($amount_in_RM, 2), 2);
            $tmp = array(
                "D",
                $row->epd_merchant_id,
                $row->epd_operator_id,
                $row->epd_terminal_id,
                $row->epd_ret_trans_ref,
                $row->epd_product_code,
                $row->epd_msisdn,
                $formated_amount,
                $request_type,
                $row->epd_trans_ref,
                $row->epd_trans_datetime,
                $row->epd_custom_field_1,
                $row->epd_custom_field_2,
                $row->epd_custom_field_3,
                $row->epd_custom_field_4,
                $row->epd_custom_field_5
            );

            $rows[] = $tmp;

            $total_detail++;
            $total_amount = $total_amount + $amount_in_RM;
            ////$last_client_trans = $row->epd_trans_datetime;
        }

        $footer = array(
            // array("T", $total_detail, number_format(round($total_amount, 2), 2)), // this format is : 1,225.75
            array("T", $total_detail, str_replace(',', '', number_format(round($total_amount, 2), 2))), // this format is : 1225.75
        );

        $header = array(
            array("H", self::CLIENT_SHORTNAME, $stringdate, 1, $last_client_trans), // enter row
        );

        return array_merge($header, $rows, $footer);
    }
    
    public function getBoughtDetail() {
        return $this->hasOne(VoucherBoughtDetail::className(), ['vod_code' => 'epd_pin']);
    }
    
    public static function find() {
        return new EpayDetailQuery(get_called_class());;
    }    

}
