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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usr_type_id', 'usr_createdate', 'usr_updated', 'usr_last_login', 'usr_last_logout', 'usr_last_sync', 'usr_last_login_old', 'usr_last_ip_numeric', 'usr_com_id', 'usr_approved', 'usr_approved_datetime', 'usr_approved_admin_id', 'usr_approved_confirm', 'usr_rejected', 'usr_rejected_datetime', 'usr_rejected_admin_id', 'usr_tablet_role', 'usr_ref_usr_id', 'usr_mal_id', 'usr_superuser'], 'integer'],
            [['usr_username', 'usr_approved_url_activation'], 'string', 'max' => 200],
            [['usr_email', 'usr_password'], 'string', 'max' => 128],
            [['usr_last_ip'], 'string', 'max' => 30],
            [['usr_rights'], 'string', 'max' => 7],
            [['usr_tablet_name', 'usr_auth_key', 'usr_device_id'], 'string', 'max' => 255],
            [['usr_username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usr_id' => 'Usr ID',
            'usr_username' => 'Usr Username',
            'usr_email' => 'Usr Email',
            'usr_password' => 'Usr Password',
            'usr_type_id' => 'Usr Type ID',
            'usr_createdate' => 'Usr Createdate',
            'usr_updated' => 'Usr Updated',
            'usr_last_login' => 'Usr Last Login',
            'usr_last_logout' => 'Usr Last Logout',
            'usr_last_sync' => 'Usr Last Sync',
            'usr_last_login_old' => 'Usr Last Login Old',
            'usr_last_ip' => 'Usr Last Ip',
            'usr_last_ip_numeric' => 'Usr Last Ip Numeric',
            'usr_rights' => 'Usr Rights',
            'usr_com_id' => 'Usr Com ID',
            'usr_approved' => 'Usr Approved',
            'usr_approved_datetime' => 'Usr Approved Datetime',
            'usr_approved_admin_id' => 'Usr Approved Admin ID',
            'usr_approved_url_activation' => 'Usr Approved Url Activation',
            'usr_approved_confirm' => 'Usr Approved Confirm',
            'usr_rejected' => 'Usr Rejected',
            'usr_rejected_datetime' => 'Usr Rejected Datetime',
            'usr_rejected_admin_id' => 'Usr Rejected Admin ID',
            'usr_tablet_name' => 'Usr Tablet Name',
            'usr_tablet_role' => 'Usr Tablet Role',
            'usr_auth_key' => 'Usr Auth Key',
            'usr_ref_usr_id' => 'Usr Ref Usr ID',
            'usr_mal_id' => 'Usr Mal ID',
            'usr_device_id' => 'Usr Device ID',
            'usr_superuser' => 'Usr Superuser',
        ];
    }
}
