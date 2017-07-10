<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_company_tag".
 *
 * @property integer $cot_id
 * @property integer $cot_com_id
 * @property integer $cot_tag_id
 * @property integer $cot_datetime_create
 * @property integer $cot_severity
 */
class CompanyTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cot_com_id', 'cot_tag_id'], 'required'],
            [['cot_com_id', 'cot_tag_id', 'cot_datetime_create', 'cot_severity'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cot_id' => 'Cot ID',
            'cot_com_id' => 'Cot Com ID',
            'cot_tag_id' => 'Cot Tag ID',
            'cot_datetime_create' => 'Cot Datetime Create',
            'cot_severity' => 'Cot Severity',
        ];
    }

    /**
     * @inheritdoc
     * @return CompanyTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyTagQuery(get_called_class());
    }
}
