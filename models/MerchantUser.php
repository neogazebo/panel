<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $usr_id
 * @property string $usr_username
 * @property string $usr_email
 * @property string $usr_password
 * @property integer $usr_type_id
 * @property integer $usr_createdate
 * @property integer $usr_updated
 * @property integer $usr_last_login
 * @property integer $usr_last_logout
 * @property integer $usr_last_sync
 * @property integer $usr_last_login_old
 * @property string $usr_last_ip
 * @property integer $usr_last_ip_numeric
 * @property string $usr_rights
 * @property integer $usr_com_id
 * @property integer $usr_approved
 * @property integer $usr_approved_datetime
 * @property integer $usr_approved_admin_id
 * @property string $usr_approved_url_activation
 * @property integer $usr_approved_confirm
 * @property integer $usr_rejected
 * @property integer $usr_rejected_datetime
 * @property integer $usr_rejected_admin_id
 * @property string $usr_tablet_name
 * @property integer $usr_tablet_role
 * @property string $usr_auth_key
 * @property integer $usr_ref_usr_id
 * @property integer $usr_mal_id
 * @property string $usr_device_id
 * @property integer $usr_superuser
 */
class MerchantUser extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_APPROVED = 1;
    const ROLE_USER = 10;
    const MEMBER = 1;
    const COMPANY = 2;
    const TABLET = 3;
    const REGISTERED_TO_TM = 'TM'; // TM SurpayPOS
    const REGISTERED_TO_POS = 'POS'; // ebizu surpayPos's users
    const REGISTERED_TO_BSC = 'BSC';
    const REGISTERED_TO_EBC = 'EBC';
    const REGISTERED_TO_RHB = 'RHB';
    const REGISTERED_TO_MGR = 'MGR'; // ebizu Manager users

    public $old_password;
    public $new_password;
    public $new_password_repeat;
    public $change_password = false;
    public $changepassword = false;
    public $activity;
    public $mall;

    public static function tableName()
    {
        return 'tbl_user';
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return [
            'usr_id' => 'ID',
            'usr_username' => 'Username',
            'usr_email' => 'Email',
            'usr_password' => 'Password',
            'usr_type_id' => 'Type',
            'usr_createdate' => 'Created On',
            'usr_last_login' => 'Last Login',
            'usr_last_logout' => 'Last Logout',
            'usr_last_sync' => 'Last Sync',
            'usr_last_login_old' => 'Last Login Old',
            'usr_last_ip' => 'Last IP',
            'usr_last_ip_numeric' => 'Last IP Numeric',
            'usr_rights' => 'Rights',
            'usr_com_id' => 'Business',
            'usr_approved' => 'Approved',
            'usr_approved_datetime' => 'Approved Datetime',
            'usr_approved_admin_id' => 'Approved Admin',
            'usr_approved_url_activation' => 'URL Activation',
            'usr_approved_confirm' => 'Approved Confirm',
            'usr_rejected' => 'Rejected',
            'usr_rejected_datetime' => 'Rejected Datetime',
            'usr_rejected_admin_id' => 'Rejected Admin',
            'usr_tablet_name' => 'Tablet Name',
            'usr_tablet_role' => 'Tablet Role',
            'usr_auth_key' => 'Auth Key',
            'usr_ref_usr_id' => 'Ref User',
            'usr_mal_id' => 'Mall',
            'new_password' => 'Password',
            'new_password_repeat' => 'Re-enter'
        ];
    }

    public function rules()
    {
        return [
            [['usr_email'], 'required', 'on' => 'signup'],
            ['usr_email', 'unique', 'on' => 'signup'],
            [['old_password', 'new_password', 'new_password_repeat'], 'required', 'on' => 'changepassword'],
            ['new_password', 'compare', 'compareAttribute' => 'new_password_repeat', 'on' => 'changepassword'],
            ['new_password', 'compare', 'compareAttribute' => 'old_password', 'operator' => '!=', 'on' => 'changepassword'],
            [['usr_email', 'new_password', 'new_password_repeat'], 'required', 'on' => 'rtm-step1'],
            ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'on' => 'rtm-step1'],
            [['usr_password'], 'required', 'on' => 'change_password'],
            [['usr_username'], 'required', 'on' => 'default'],
            [['usr_approved', 'usr_approved_datetime', 'usr_approved_admin_id', 'usr_approved_url_activation', 'usr_approved_confirm'], 'required', 'on' => 'approved'],
            [['usr_rejected', 'usr_rejected_datetime', 'usr_rejected_admin_id'], 'required', 'on' => 'rejected'],
            [['change_password'], 'safe'],
            ['usr_email', 'validateEmailRegisteredAsCustomer', 'on' => 'register-member'],
        ];
    }
    
    /**
     * Function for validate email account customers
     * 
     * @author Tajhul Faijin <tajhul@ebizu.com>
     */
    public function validateEmailRegisteredAsCustomer($attribute, $params)
    {
        $model = Member::find()
            ->joinWith('customer')
            ->where('mem_email=:email AND cus_com_id=:com_id',[
                'email'=>$this->$attribute, 
                'com_id'=>Yii::$app->loggedin->company->com_id
            ]);

        if($model->count()) {
            $this->addError($attribute, 'The email <strong>'.$this->$attribute.'</strong> has been registered as customer on this merchant.');
        } else {
            return true;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->usr_last_ip = $_SERVER['REMOTE_ADDR'];
                $this->usr_createdate = time();
                $this->usr_last_login = time();
            }
            if ($this->change_password) {
                $this->usr_password = md5($this->new_password);
            }
            return true;
        }
        return false;
    }

    public function getCompanyRegisterModel()
    {
        $company = new Company;
        $company->setScenario('signup');
        $company->com_currency = 'MYR';
        $company->com_timezone = '268';
        $company->load(Yii::$app->request->post());
        return $company;
    }

    public function getPrincipalRegisterModel()
    {
        $company = new Principal;
        $company->setScenario('signup');
        $company->prc_currency = 'MYR';
        $company->prc_timezone = '268';
        $company->load(Yii::$app->request->post());
        return $company;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (parent::afterSave($insert, $changedAttributes)) {
            
        }
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUserEmail($username)
    {
        return static::findOne(['usr_email' => $username, 'usr_approved' => self::STATUS_APPROVED]);
    }

    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->usr_auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
        //return true;
    }

    public function validatePassword($password)
    {
        return md5($password);
    }

    public function setPassword($password)
    {
        $this->usr_password = md5($password);
    }

    public function generateAuthKey()
    {
        $this->usr_auth_key = Security::generateRandomKey();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['com_usr_id' => 'usr_id']);
    }

    public function getPrincipal()
    {
        return $this->hasOne(Principal::className(), ['prc_usr_id' => 'usr_id']);
    }

    public function getModelPaymentForm()
    {
        return new PaymentForm();
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['mem_usr_id' => 'usr_id']);
    }

    public function getType()
    {
        return Yii::$app->user->identity->usr_type_id;
    }
    
    public function getFollowers()
    {
        return $this->hasMany(Follow::className(), ['fol_following_usr_id' => 'usr_id']);
    }

    public function getIsMall()
    {
        return $this->hasOne(Mall::className(), ['mal_id' => 'usr_mal_id']);
    }

    public function getSession()
    {
        return $this->hasMany(Session::className(), ['ses_usr_id' => 'usr_id']);
    }

    public static function topGender($date)
    {
        $query = self::find()
            ->select(['IF(mem_gender="M", "Male", IF(mem_gender="F", "Female", "Unknown")) as label', 'COUNT(*) as value'])
            ->from('tbl_user')
            ->join('inner join', 'tbl_member', 'mem_usr_id = usr_id')
            ->where('usr_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
            ->groupBy('mem_gender');
        //->orderBy('count(*) DESC');
        //->limit(10);
        $command = $query->createCommand();
        $rows = $command->queryAll();

        $data = [['category', 'category']];

        foreach ($rows as $row) {
            $data[] = [0 => $row['label'], 1 => (int) $row['value']];
        }

        return $data;
    }

    public function behaviors()
    {
        return [
            'getQuery' => [
                'class' => 'app\components\behaviors\WorkerQueryBehavior',
                'tableName' => self::tableName(),
                'fieldIdName' => 'usr_id',
                'version' => 'v1.0',
                'type' => 'high'
            ]
        ];
    }
}
