<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class MallMerchant extends EbizuActiveRecord
{

    public $mac_id;
    public $mac_name;
    public $mam_category;
    public $idTemp;

    public static function find()
    {
        return new MallMerchantQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'tbl_mall_merchant';
    }

    /* update by sandi , 'mam_floor', 'mam_unit_number' on signup can not required */

    public function rules()
    {
        return [
            [['mam_mal_id', 'mam_com_id'], 'required'],
            [['mam_mal_id', 'mam_com_id'], 'safe','on' => 'newMerchant'],
            [['mam_floor', 'mam_unit_number', 'mam_category'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mam_category' => 'Mall Category',
            'mam_com_id' => 'Merchant',
            'mam_mal_id' => 'Mall Name',
            'mam_floor' => 'Level',
            'mam_unit_number' => 'Unit Number',
        ];
    }

    public function getMall()
    {
        return $this->hasOne(Mall::className(), ['mal_id' => 'mam_mal_id']);
    }

    public function getMallCategory($id)
    {
        $model = self::find()
        ->select('b.mac_id, b.mac_name')
        ->leftJoin('tbl_mall_category b', 'b.mac_mal_id = mam_mal_id')
        ->where(['mam_mal_id' => $id])
        ->groupBy('b.mac_id')
        ->all();
        return \yii\helpers\ArrayHelper::map($model, 'mac_id', 'mac_name');
    }

    public function getBusiness()
    {
        return $this->hasOne(Company::className(), ['com_id' => 'mam_com_id']);
    }

    public static function getFloor($id)
    {
        $model = FloorPlanMall::find()->where('fpm_mal_id = :mal_id', [':mal_id' => $id])->all();
        return \yii\helpers\ArrayHelper::map($model, 'fpm_name', 'fpm_name');
    }

    public static function getUnit($id)
    {
        $model = FloorPlanUnit::find()
        ->innerJoin('tbl_floor_plan_mall b', 'b.fpm_id = fpu_fpm_id')
        ->where('b.fpm_mal_id = :mal_id', [':mal_id' => $id])
        ->all();
        return \yii\helpers\ArrayHelper::map($model, 'fpu_name', 'fpu_name');
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert)
        {
            if (!empty($this->idTemp))
            {
                $floorplan_merchants = FloorPlanMallMerchant::findAll(['fpm_temp_id' => $this->idTemp]);
                if (count($floorplan_merchants) != 0)
                {
                    foreach ($floorplan_merchants as $data)
                    {
                        $data->fpm_mam_id = $this->mam_id;
                        $data->fpm_temp_id = 0;
                        $data->save(false);
                    }
                }
            }
        }
    }

    public function resetFloorplan()
    {
        $floor_plan = FloorPlanMallMerchant::findAll(['fpm_mam_id' => $this->mam_id]);
        if (count($floor_plan) != 0)
        {
            foreach ($floor_plan as $data)
            {
                $data->delete();
            }
        }
    }

}
