<?php

namespace app\models;

/**
 * This is the model class for table "tbl_company_category".
 *
 * @property integer $com_category_id
 * @property string $com_category
 * @property integer $com_parent_category_id
 * @property string $com_icon
 * @property string $com_category_type
 */
class CompanyCategory extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tbl_company_category';
	}

	public function getCompany() {
		return $this->hasMany(Company::className(), ['com_subcategory_id'=>'com_category_id']);
	}

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->com_category = mb_convert_encoding($this->com_category, 'UTF-8');
            return true;
        }
        return false;
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['com_category', 'com_parent_category_id', 'com_category_type'], 'required'],
			[['com_parent_category_id'], 'integer'],
			[['com_category'], 'string', 'max' => 50],
			[['com_icon'], 'string', 'max' => 150]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'com_category_id' => 'ID',
			'com_category' => 'Category',
			'com_parent_category_id' => 'Parent',
			'com_icon' => 'Icon',
			'com_category_type' => 'Type'
		];
	}

	public function listParent($type)
	{
        $model = self::find()
        	->where('com_category_type = :type', [':type' => $type])
        	->groupBy('com_parent_category_id')
        	->orderBy('com_category')
        	->all();
        return \common\components\helpers\Html::listData($model, 'com_category_id', 'com_category');
	}

	public function getParentcategory()
    {
        return $this->hasMany(self::className(), ['com_parent_category_id' => 'com_parent_category_id']);
    }

    public function getSubcategory()
    {
        return $this->hasOne(CompanyCategory::className(), ['com_parent_category_id' => 'com_parent_category_id']);
    }

}
