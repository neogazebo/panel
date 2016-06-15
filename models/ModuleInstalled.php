<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_module_installed".
 *
 * @property integer $mos_id
 * @property integer $mos_mod_id
 * @property integer $mos_com_id
 * @property integer $mos_datetime
 * @property string $mos_active
 * @property string $mos_key
 * @property string $mos_secret
 * @property integer $mos_page_builder
 * @property integer $mos_delete
 */
class ModuleInstalled extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_module_installed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mos_mod_id', 'mos_com_id', 'mos_datetime', 'mos_active'], 'required'],
            [['mos_mod_id', 'mos_com_id', 'mos_datetime', 'mos_page_builder', 'mos_delete'], 'integer'],
            [['mos_active'], 'string'],
            [['mos_key', 'mos_secret'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mos_id' => 'Mos ID',
            'mos_mod_id' => 'Mos Mod ID',
            'mos_com_id' => 'Mos Com ID',
            'mos_datetime' => 'Mos Datetime',
            'mos_active' => 'Mos Active',
            'mos_key' => 'Mos Key',
            'mos_secret' => 'Mos Secret',
            'mos_page_builder' => 'Mos Page Builder',
            'mos_delete' => 'Mos Delete',
        ];
    }

    /**
     * @inheritdoc
     * @return ModuleInstalledQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ModuleInstalledQuery(get_called_class());
    }
}
