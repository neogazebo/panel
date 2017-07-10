<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $cty_id
 * @property string $cty_short_code
 * @property string $cty_name
 * @property string $cty_currency_name_iso2
 * @property string $cty_currency_name_iso3
 * @property string $cty_currency_symbol
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_country';
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
            [['cty_short_code', 'cty_name', 'cty_currency_name_iso2', 'cty_currency_name_iso3', 'cty_currency_symbol'], 'required'],
            [['cty_short_code', 'cty_currency_name_iso2'], 'string', 'max' => 2],
            [['cty_name'], 'string', 'max' => 25],
            [['cty_currency_name_iso3', 'cty_currency_symbol'], 'string', 'max' => 3],
            [['cty_short_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cty_id' => 'Cty ID',
            'cty_short_code' => 'Cty Short Code',
            'cty_name' => 'Cty Name',
            'cty_currency_name_iso2' => 'Cty Currency Name Iso2',
            'cty_currency_name_iso3' => 'Cty Currency Name Iso3',
            'cty_currency_symbol' => 'Cty Currency Symbol',
        ];
    }

    /**
     * @inheritdoc
     * @return CountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountryQuery(get_called_class());
    }
}
