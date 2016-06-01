<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_category".
 *
 * @property integer $cat_id
 * @property string $cat_name
 * @property integer $cat_created
 * @property integer $cat_updated
 * @property integer $cat_status
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_category';
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
            [['cat_name'], 'required'],
            [['cat_created', 'cat_updated', 'cat_status'], 'integer'],
            [['cat_name'], 'string', 'max' => 50],
            [['cat_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'ID',
            'cat_name' => 'Category',
            'cat_created' => 'Created',
            'cat_updated' => 'Updated',
            'cat_status' => 'Status',
        ];
    }
}
