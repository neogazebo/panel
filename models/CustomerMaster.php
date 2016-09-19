<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_customer".
 *
 * @property integer $cus_id
 * @property integer $cus_mem_id
 * @property integer $cus_com_id
 * @property integer $cus_datetime
 * @property integer $cus_group
 * @property integer $cus_spent_credit
 * @property integer $cus_earn_point
 * @property integer $cus_redeem_point
 * @property integer $cus_last_check_in
 * @property integer $cus_last_earn_point
 * @property integer $cus_last_redeem_point
 * @property integer $cus_last_bought
 * @property integer $cus_datetime_confirm
 * @property integer $cus_status
 * @property string $cus_key
 * @property integer $cus_cuf_id
 * @property string $cus_sync_id
 * @property integer $cus_last_sync_time
 * @property integer $cus_created_by
 * @property integer $cus_created_date
 * @property integer $cus_updated_by
 * @property integer $cus_updated_date
 * @property integer $cus_active_status
 */
class CustomerMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cus_mem_id', 'cus_com_id'], 'required'],
            [['cus_mem_id', 'cus_com_id', 'cus_datetime', 'cus_group', 'cus_spent_credit', 'cus_earn_point', 'cus_redeem_point', 'cus_last_check_in', 'cus_last_earn_point', 'cus_last_redeem_point', 'cus_last_bought', 'cus_datetime_confirm', 'cus_status', 'cus_cuf_id', 'cus_last_sync_time', 'cus_created_by', 'cus_created_date', 'cus_updated_by', 'cus_updated_date', 'cus_active_status'], 'integer'],
            [['cus_key'], 'string', 'max' => 200],
            [['cus_sync_id'], 'string', 'max' => 100],
            [['cus_com_id', 'cus_mem_id'], 'unique', 'targetAttribute' => ['cus_com_id', 'cus_mem_id'], 'message' => 'The combination of Cus Mem ID and Cus Com ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cus_id' => 'Cus ID',
            'cus_mem_id' => 'Cus Mem ID',
            'cus_com_id' => 'Cus Com ID',
            'cus_datetime' => 'Cus Datetime',
            'cus_group' => 'Cus Group',
            'cus_spent_credit' => 'Cus Spent Credit',
            'cus_earn_point' => 'Cus Earn Point',
            'cus_redeem_point' => 'Cus Redeem Point',
            'cus_last_check_in' => 'Cus Last Check In',
            'cus_last_earn_point' => 'Cus Last Earn Point',
            'cus_last_redeem_point' => 'Cus Last Redeem Point',
            'cus_last_bought' => 'Cus Last Bought',
            'cus_datetime_confirm' => 'Cus Datetime Confirm',
            'cus_status' => 'Cus Status',
            'cus_key' => 'Cus Key',
            'cus_cuf_id' => 'Cus Cuf ID',
            'cus_sync_id' => 'Cus Sync ID',
            'cus_last_sync_time' => 'Cus Last Sync Time',
            'cus_created_by' => 'Cus Created By',
            'cus_created_date' => 'Cus Created Date',
            'cus_updated_by' => 'Cus Updated By',
            'cus_updated_date' => 'Cus Updated Date',
            'cus_active_status' => 'Cus Active Status',
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerMasterQuery(get_called_class());
    }
}
