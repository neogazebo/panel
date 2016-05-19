<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_tag".
 *
 * @property integer $tag_id
 * @property string $tag_category
 * @property integer $tag_com_category_id
 * @property string $tag_name
 * @property integer $tag_nr_male
 * @property integer $tag_nr_female
 * @property integer $tag_nr_total
 * @property integer $tag_creator
 * @property integer $tag_datetime_create
 * @property integer $tag_datetime_update
 * @property integer $tag_moderated
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new TagQuery(get_called_class());
    }


    public static function tableName()
    {
        return 'tbl_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_category'], 'string'],
            [['tag_com_category_id', 'tag_nr_male', 'tag_nr_female', 'tag_nr_total', 'tag_creator', 'tag_datetime_create', 'tag_datetime_update', 'tag_moderated'], 'integer'],
            [['tag_name'], 'required'],
            [['tag_name'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'ID',
            'tag_category' => 'Category',
            'tag_com_category_id' => 'Business Category',
            'tag_name' => 'Name',
            'tag_nr_male' => 'Nr Male',
            'tag_nr_female' => 'Nr Female',
            'tag_nr_total' => 'Nr Total',
            'tag_creator' => 'Creator',
            'tag_datetime_create' => 'Created On',
            'tag_datetime_update' => 'Updated On',
            'tag_moderated' => 'Moderated',
        ];
    }
    
}
