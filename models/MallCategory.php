<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_mall_category".
 *
 * @property integer $mac_id
 * @property integer $mac_parent_id
 * @property string $mac_name
 * @property integer $mac_mal_id
 */
class MallCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mall_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mac_parent_id', 'mac_mal_id'], 'integer'],
            [['mac_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac_id' => 'Mac ID',
            'mac_parent_id' => 'Mac Parent ID',
            'mac_name' => 'Mac Name',
            'mac_mal_id' => 'Mac Mal ID',
        ];
    }

    /**
     * @inheritdoc
     * @return MallCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MallCategoryQuery(get_called_class());
    }
}
