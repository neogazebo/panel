<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_module".
 *
 * @property integer $mod_id
 * @property integer $mod_owner
 * @property string $mod_code
 * @property string $mod_name
 * @property string $mod_description
 * @property string $mod_short_description
 * @property string $mod_icon
 * @property string $mod_icon_web
 * @property string $mod_url
 * @property string $mod_system
 * @property integer $mod_datetime
 * @property integer $mod_front_page
 * @property string $mod_url_front
 * @property integer $mod_sequence
 * @property integer $mod_mobile_ready
 * @property string $mod_screenshot
 * @property integer $mod_disable
 * @property integer $mod_mobile_ios
 * @property integer $mod_mobile_android
 * @property integer $mod_tablet_ios
 * @property integer $mod_tablet_android
 * @property integer $mod_web_status
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mod_owner', 'mod_datetime', 'mod_front_page', 'mod_sequence', 'mod_mobile_ready', 'mod_disable', 'mod_mobile_ios', 'mod_mobile_android', 'mod_tablet_ios', 'mod_tablet_android', 'mod_web_status'], 'integer'],
            [['mod_code', 'mod_name', 'mod_icon', 'mod_url', 'mod_datetime'], 'required'],
            [['mod_description', 'mod_short_description', 'mod_system'], 'string'],
            [['mod_code', 'mod_screenshot'], 'string', 'max' => 100],
            [['mod_name'], 'string', 'max' => 300],
            [['mod_icon', 'mod_icon_web', 'mod_url', 'mod_url_front'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mod_id' => 'ID',
            'mod_owner' => 'Owner',
            'mod_code' => 'Code',
            'mod_name' => 'Name',
            'mod_description' => 'Description',
            'mod_short_description' => 'Short Description',
            'mod_icon' => 'Icon',
            'mod_icon_web' => 'Icon Web',
            'mod_url' => 'Url',
            'mod_system' => 'System',
            'mod_datetime' => 'Datetime',
            'mod_front_page' => 'Front Page',
            'mod_url_front' => 'Url Front',
            'mod_sequence' => 'Sequence',
            'mod_mobile_ready' => 'Mobile Ready',
            'mod_screenshot' => 'Screenshot',
            'mod_disable' => 'Disable',
            'mod_mobile_ios' => 'Mobile Ios',
            'mod_mobile_android' => 'Mobile Android',
            'mod_tablet_ios' => 'Tablet Ios',
            'mod_tablet_android' => 'Tablet Android',
            'mod_web_status' => 'Web Status',
        ];
    }

    /**
     * @inheritdoc
     * @return ModuleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ModuleQuery(get_called_class());
    }
}
