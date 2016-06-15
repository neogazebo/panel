<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_feature_subscription_company".
 *
 * @property integer $fsc_id
 * @property integer $fsc_com_id
 * @property integer $fsc_fes_id
 * @property integer $fsc_datetime
 * @property integer $fsc_valid_start
 * @property integer $fsc_valid_end
 * @property integer $fsc_status
 * @property integer $fsc_counter_index
 * @property integer $fsc_counter_alias_start
 * @property integer $fsc_counter_alias_end
 * @property integer $fsc_free
 * @property integer $fsc_trial
 * @property integer $fsc_status_datetime
 * @property double $fsc_payment_amount
 * @property string $fsc_payment_currency
 * @property integer $fsc_payment_type
 * @property integer $fsc_payment_datetime
 * @property integer $fsc_payment_received_datetime
 * @property integer $fsc_payment_received_by
 * @property integer $fsc_suspend_datetime
 * @property integer $fsc_block_datetime
 * @property string $fsc_payment_method
 * @property string $fsc_payment_ref
 */
class FeatureSubscriptionCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_feature_subscription_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fsc_com_id', 'fsc_fes_id', 'fsc_datetime', 'fsc_valid_start', 'fsc_valid_end', 'fsc_status', 'fsc_counter_index', 'fsc_counter_alias_start', 'fsc_counter_alias_end', 'fsc_free', 'fsc_trial', 'fsc_status_datetime', 'fsc_payment_type', 'fsc_payment_datetime', 'fsc_payment_received_datetime', 'fsc_payment_received_by', 'fsc_suspend_datetime', 'fsc_block_datetime'], 'integer'],
            [['fsc_payment_amount'], 'number'],
            [['fsc_payment_currency'], 'string', 'max' => 5],
            [['fsc_payment_method'], 'string', 'max' => 45],
            [['fsc_payment_ref'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fsc_id' => 'Fsc ID',
            'fsc_com_id' => 'Fsc Com ID',
            'fsc_fes_id' => 'Fsc Fes ID',
            'fsc_datetime' => 'Fsc Datetime',
            'fsc_valid_start' => 'Fsc Valid Start',
            'fsc_valid_end' => 'Fsc Valid End',
            'fsc_status' => 'Fsc Status',
            'fsc_counter_index' => 'Fsc Counter Index',
            'fsc_counter_alias_start' => 'Fsc Counter Alias Start',
            'fsc_counter_alias_end' => 'Fsc Counter Alias End',
            'fsc_free' => 'Fsc Free',
            'fsc_trial' => 'Fsc Trial',
            'fsc_status_datetime' => 'Fsc Status Datetime',
            'fsc_payment_amount' => 'Fsc Payment Amount',
            'fsc_payment_currency' => 'Fsc Payment Currency',
            'fsc_payment_type' => 'Fsc Payment Type',
            'fsc_payment_datetime' => 'Fsc Payment Datetime',
            'fsc_payment_received_datetime' => 'Fsc Payment Received Datetime',
            'fsc_payment_received_by' => 'Fsc Payment Received By',
            'fsc_suspend_datetime' => 'Fsc Suspend Datetime',
            'fsc_block_datetime' => 'Fsc Block Datetime',
            'fsc_payment_method' => 'Fsc Payment Method',
            'fsc_payment_ref' => 'Fsc Payment Ref',
        ];
    }

    /**
     * @inheritdoc
     * @return FeatureSubscriptionCompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeatureSubscriptionCompanyQuery(get_called_class());
    }
}
