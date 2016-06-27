<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_company_suggestion".
 *
 * @property integer $cos_id
 * @property integer $cos_com_id
 * @property integer $cos_sna_id
 * @property string $cos_name
 * @property string $cos_mall
 * @property string $cos_location
 * @property integer $cos_datetime
 */
class CompanySuggestion extends \yii\db\ActiveRecord
{
    public $cos_mall_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company_suggestion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cos_com_id', 'cos_sna_id', 'cos_datetime'], 'integer'],
            [['cos_name', 'cos_mall'], 'string', 'max' => 200],
            [['cos_location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cos_id' => 'ID',
            'cos_com_id' => 'Merchant',
            'cos_sna_id' => 'Snap & Earn',
            'cos_name' => 'Name',
            'cos_mall' => 'Mall',
            'cos_location' => 'Location',
            'cos_datetime' => 'Created On',
        ];
    }
}
