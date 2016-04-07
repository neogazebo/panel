<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "tbl_admin_user".
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const TYPE_ADMIN = 1;
    const TYPE_TM = 2;
    const TYPE_MALL = 3;
    const TYPE_SALES = 4;
    const LEVEL_SUPER_USER = 1;
    const LEVEL_ADMIN = 2;
    const LEVEL_MODERATOR = 3;

    public $password;
    public $password_repeat;
    public $old_password;

    public static function tableName()
    {
        return 'tbl_admin_user';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }

    public function rules()
    {
        new ProductItem;
        return [
            [['email'], 'unique'],
            [['email'], 'email'],
            [['country'], 'safe', 'on' => 'update'],
            [['username', 'email'], 'required', 'on' => 'update'],
            [['username', 'email', 'password', 'password_repeat'], 'required', 'on' => 'create-profile'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],
            ['level', 'default', 'value' => self::LEVEL_ADMIN],
            ['level', 'in', 'range' => [self::LEVEL_SUPER_USER, self::LEVEL_ADMIN, self::LEVEL_MODERATOR]],
            [['username', 'mall', 'type', 'mall_role_id'], 'safe'],
            ['old_password', 'required', 'on' => 'changepass'],
            ['old_password', function ($attribute, $params) {
                if (!$this->validatePassword($this->$attribute)) {
                    $this->addError($attribute, 'Your old password did not match');
                }
            }],
            [['old_password', 'password', 'password_repeat'], 'required', 'on' => 'changepassword'],
            ['password', 'compare', 'compareAttribute' => 'old_password', 'operator' => '!=', 'on' => 'changepassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'type' => 'Type',
            'mall' => 'Mall',
            'mall_role_id' => 'Group',
            'password_confirm' => 'Repeat Password',
            'country' => 'Country'
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::find()
            ->where('
                (username = :username OR 
                    email = :email) 
                AND status = :status
            ', [
                ':username' => $email,
                ':email' => $email,
                ':status' => self::STATUS_ACTIVE
            ])->one();
    }

    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
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
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setPasswordAccount();
            } else {
                if ($this->getScenario() != 'update') {
                    $this->setPasswordAccount();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    protected function setPasswordAccount()
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        $this->auth_key = Yii::$app->security->generateRandomString();
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

}
