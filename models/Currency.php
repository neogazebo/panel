<?php

namespace app\models;

use Yii;

class Currency extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'tbl_currency';
    }


    public function rules()
    {
        return [
            [['cur_id', 'cur_name'], 'required'],
            [['cur_id'], 'string', 'max' => 5],
            [['cur_name'], 'string', 'max' => 200],
            [['cur_symbol'], 'string', 'max' => 100]
        ];
    }


    public function attributeLabels()
    {
        return [
            'cur_id' => 'ID',
            'cur_name' => 'Currency',
            'cur_symbol' => 'Symbol',
        ];
    }
    
    public static function symbol($code){
        $model = self::find()->where('cur_id=:cur_id', [
            ':cur_id'=>$code
        ])->one();
        if(!empty($model->cur_symbol))
            return $model->cur_symbol;
    }
}
