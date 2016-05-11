<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_merchant_signup".
 *
 * @property integer $id
 * @property string $mer_bussines_name
 * @property string $mer_company_name
 * @property string $mer_bussiness_description
 * @property integer $mer_bussines_type_retail
 * @property integer $mer_bussines_type_service
 * @property integer $mer_bussines_type_franchise
 * @property integer $mer_bussines_type_pro_services
 * @property string $mer_address
 * @property string $mer_post_code
 * @property integer $mer_office_phone
 * @property integer $mer_office_fax
 * @property string $mer_website
 * @property integer $mer_multichain
 * @property string $mer_multichain_file
 * @property string $mer_login_email
 * @property string $mer_pic_name
 * @property string $mer_contact_phone
 * @property string $mer_contact_mobile
 * @property string $mer_contact_email
 * @property integer $mer_preferr_comm_mail
 * @property integer $mer_preferr_comm_email
 * @property integer $mer_preferr_comm_mobile_phone
 * @property string $mer_agent_code
 * @property integer $mer_applicant_acknowledge
 * @property integer $created_date
 * @property integer $updated_date
 */
class MerchantSignup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_merchant_signup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mer_bussiness_description', 'mer_address', 'mer_multichain_file'], 'string'],
            [['mer_bussines_type_retail', 'mer_bussines_type_service', 'mer_bussines_type_franchise', 'mer_bussines_type_pro_services', 'mer_office_phone', 'mer_office_fax', 'mer_multichain', 'mer_preferr_comm_mail', 'mer_preferr_comm_email', 'mer_preferr_comm_mobile_phone', 'mer_applicant_acknowledge', 'created_date', 'updated_date'], 'integer'],
            [['mer_bussines_name', 'mer_company_name', 'mer_website', 'mer_login_email', 'mer_pic_name', 'mer_contact_phone', 'mer_contact_mobile', 'mer_contact_email', 'mer_agent_code'], 'string', 'max' => 255],
            [['mer_post_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mer_bussines_name' => 'Mer Bussines Name',
            'mer_company_name' => 'Mer Company Name',
            'mer_bussiness_description' => 'Mer Bussiness Description',
            'mer_bussines_type_retail' => '0=disable, 1=enable',
            'mer_bussines_type_service' => '0=disable, 1=enable',
            'mer_bussines_type_franchise' => '0=disable, 1=enable',
            'mer_bussines_type_pro_services' => '0=disable, 1=enable',
            'mer_address' => 'Mer Address',
            'mer_post_code' => 'Mer Post Code',
            'mer_office_phone' => 'Mer Office Phone',
            'mer_office_fax' => 'Mer Office Fax',
            'mer_website' => 'Mer Website',
            'mer_multichain' => '0=disable, 1=enable',
            'mer_multichain_file' => 'Mer Multichain File',
            'mer_login_email' => 'Mer Login Email',
            'mer_pic_name' => 'Mer Pic Name',
            'mer_contact_phone' => 'Mer Contact Phone',
            'mer_contact_mobile' => 'Mer Contact Mobile',
            'mer_contact_email' => 'Mer Contact Email',
            'mer_preferr_comm_mail' => '0=disable, 1=enable',
            'mer_preferr_comm_email' => '0=disable, 1=enable',
            'mer_preferr_comm_mobile_phone' => '0=disable, 1=enable',
            'mer_agent_code' => 'Mer Agent Code',
            'mer_applicant_acknowledge' => '0=disable, 1=enable',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * @inheritdoc
     * @return MerchantSignupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MerchantSignupQuery(get_called_class());
    }
}
