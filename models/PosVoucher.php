<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_pos_voucher".
 *
 * @property string $pvo_id
 * @property integer $pvo_com_id
 * @property string $pvo_name
 * @property string $pvo_description
 * @property string $pvo_image
 * @property string $pvo_pvt_id
 * @property string $pvo_amount
 * @property integer $pvo_minimum_spend
 * @property string $pvo_type
 * @property integer $pvo_valid_start
 * @property integer $pvo_valid_end
 * @property integer $pvo_stock_left
 * @property integer $pvo_stock_minimum
 * @property integer $pvo_active_status
 * @property string $pvo_sync_id
 * @property integer $pvo_sync_datetime
 * @property integer $pvo_create_datetime
 * @property integer $pvo_created_by
 * @property integer $pvo_update_datetime
 * @property integer $pvo_updated_by
 */
class PosVoucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_pos_voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pvo_id'], 'required'],
            [['pvo_com_id', 'pvo_minimum_spend', 'pvo_valid_start', 'pvo_valid_end', 'pvo_stock_left', 'pvo_stock_minimum', 'pvo_active_status', 'pvo_sync_datetime', 'pvo_create_datetime', 'pvo_created_by', 'pvo_update_datetime', 'pvo_updated_by'], 'integer'],
            [['pvo_description', 'pvo_type'], 'string'],
            [['pvo_id', 'pvo_pvt_id', 'pvo_amount'], 'string', 'max' => 50],
            [['pvo_name'], 'string', 'max' => 150],
            [['pvo_image'], 'string', 'max' => 200],
            [['pvo_sync_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pvo_id' => 'ID',
            'pvo_com_id' => 'Merchant',
            'pvo_name' => 'Name',
            'pvo_description' => 'Description',
            'pvo_image' => 'Image',
            'pvo_pvt_id' => 'Pvt ID',
            'pvo_amount' => 'Amount',
            'pvo_minimum_spend' => 'Minimum Spend',
            'pvo_type' => 'Type',
            'pvo_valid_start' => 'Valid Start',
            'pvo_valid_end' => 'Valid End',
            'pvo_stock_left' => 'Stock Left',
            'pvo_stock_minimum' => 'Stock Minimum',
            'pvo_active_status' => 'Active Status',
            'pvo_sync_id' => 'Sync ID',
            'pvo_sync_datetime' => 'Sync Datetime',
            'pvo_create_datetime' => 'Create Datetime',
            'pvo_created_by' => 'Created By',
            'pvo_update_datetime' => 'Update Datetime',
            'pvo_updated_by' => 'Updated By',
        ];
    }
}
