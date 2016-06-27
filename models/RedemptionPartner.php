<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_redemption_partner".
 *
 * @property integer $red_id
 * @property string $red_name
 * @property string $red_email
 * @property string $red_email_sender
 * @property string $red_password
 * @property string $red_key
 * @property string $red_hash
 * @property string $red_callback
 * @property string $red_logo
 * @property integer $red_callback_active
 * @property integer $red_status
 * @property integer $red_address
 * @property string $red_post_code
 * @property string $red_city
 * @property integer $red_city_id
 * @property integer $red_region_id
 * @property integer $red_country_id
 * @property string $red_phone
 * @property string $red_tax_number
 * @property integer $red_create_datetime
 * @property integer $red_update_datetime
 */
class RedemptionPartner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_redemption_partner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['red_hash'], 'string'],
            [['red_callback_active', 'red_status', 'red_address', 'red_city_id', 'red_region_id', 'red_country_id', 'red_create_datetime', 'red_update_datetime'], 'integer'],
            [['red_name', 'red_email', 'red_email_sender', 'red_password', 'red_key', 'red_logo', 'red_phone', 'red_tax_number'], 'string', 'max' => 100],
            [['red_callback'], 'string', 'max' => 255],
            [['red_post_code'], 'string', 'max' => 20],
            [['red_city'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'red_id' => 'ID',
            'red_name' => 'Name',
            'red_email' => 'Email',
            'red_email_sender' => 'Email Sender',
            'red_password' => 'Password',
            'red_key' => 'Key',
            'red_hash' => 'Hash',
            'red_callback' => 'Callback',
            'red_logo' => 'Logo',
            'red_callback_active' => 'Callback Active',
            'red_status' => 'Status',
            'red_address' => 'Address',
            'red_post_code' => 'Post Code',
            'red_city' => 'City',
            'red_city_id' => 'City ID',
            'red_region_id' => 'Region ID',
            'red_country_id' => 'Country ID',
            'red_phone' => 'Phone',
            'red_tax_number' => 'Tax Number',
            'red_create_datetime' => 'Create Datetime',
            'red_update_datetime' => 'Update Datetime',
        ];
    }
}
