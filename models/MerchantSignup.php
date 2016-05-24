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
 * @property integer $mer_prefer_office_phone
 * @property integer $mer_preferr_comm_mobile_phone
 * @property string $mer_agent_code
 * @property integer $mer_applicant_acknowledge
 * @property integer $created_date
 * @property integer $updated_date
 * @property integer $mer_reviewed
 */
class MerchantSignup extends EbizuActiveRecord
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
            [['mer_bussines_name', 'mer_company_name', 'mer_login_email'], 'required'],
            [['mer_login_email', 'mer_contact_email'], 'email'],
            ['mer_login_email', 'unique'],
            // [['mer_login_email'], 'validateEmail'],
            [['mer_website'], 'url'],
            [['mer_bussiness_description', 'mer_address'], 'string'],
            [['mer_bussines_type_retail', 'mer_bussines_type_service', 'mer_bussines_type_franchise', 'mer_bussines_type_pro_services', 'mer_office_phone', 'mer_office_fax', 'mer_multichain', 'mer_preferr_comm_mail', 'mer_preferr_comm_email', 'mer_preferr_comm_mobile_phone', 'mer_applicant_acknowledge', 'created_date', 'updated_date', 'mer_reviewed'], 'integer'],
            [['mer_bussines_name', 'mer_company_name', 'mer_website', 'mer_login_email', 'mer_pic_name', 'mer_contact_phone', 'mer_contact_mobile', 'mer_contact_email', 'mer_agent_code'], 'string', 'max' => 255],
            [['mer_post_code'], 'string', 'max' => 10],
            [['mer_multichain_file'], 'file', 'extensions' => 'xls, xlsx, csv'],
            [['mer_bussines_name', 'mer_company_name', 'mer_login_email'], 'safe', 'on' => 'review']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mer_bussines_name' => 'Business Name',
            'mer_company_name' => 'Company Name',
            'mer_bussiness_description' => 'Business Description',
            'mer_bussines_type_retail' => 'Retail',
            'mer_bussines_type_service' => 'Service',
            'mer_bussines_type_franchise' => 'Franchise',
            'mer_bussines_type_pro_services' => 'Professional Services',
            'mer_address' => 'Business / Office Address',
            'mer_post_code' => 'Postcode',
            'mer_office_phone' => 'Office Phone Number',
            'mer_office_fax' => 'Office Fax Number',
            'mer_website' => 'Company Website',
            'mer_multichain' => 'Multichain',
            'mer_multichain_file' => 'Multichain File',
            'mer_login_email' => 'Preferred Login ID (Email Format)',
            'mer_pic_name' => 'PIC Name',
            'mer_contact_phone' => 'Office Phone Number',
            'mer_contact_mobile' => 'Mobile Number',
            'mer_contact_email' => 'Email Address',
            'mer_preferr_comm_mail' => 'Mail',
            'mer_preferr_comm_email' => 'E-Mail',
            'mer_prefer_office_phone' => 'Office Phone',
            'mer_preferr_comm_mobile_phone' => 'Mobile Phone',
            'mer_agent_code' => 'Agent Code',
            'mer_applicant_acknowledge' => 'Applicant Acknowledgement',
            'created_date' => 'Created At',
            'updated_date' => 'Updated At',
            'mer_reviewed' => 'Reviewed',
        ];
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_date', 'updated_date'],                    
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_date'],
                ],
            ]
        ];
    }

    public function validateEmail($attribute, $params)
    {
        $num_row = $this->find()->where(['mer_login_email' => $this->$attribute])->count();
        if ($num_row > 0) {
            $this->addError($attribute, 'Email Already Exist!');
        }
    }

    public function getFile()
    {
        if (!empty($this->mer_multichain_file))
            return Yii::$app->params['businessUrl'] . $this->mer_multichain_file;
        return Yii::$app->homeUrl . 'img/90.jpg';
    }
}
