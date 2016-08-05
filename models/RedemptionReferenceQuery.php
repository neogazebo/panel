<?php

namespace app\models;

use Yii;

use app\components\helpers\Utc;

/**
 * This is the ActiveQuery class for [[RedemptionReference]].
 *
 * @see RedemptionReference
 */
class RedemptionReferenceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RedemptionReference[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RedemptionReference|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function findCostume()
    {
        $username = Yii::$app->request->get('username');
        $msisdn = Yii::$app->request->get('rwd_msisdn');
        $status = Yii::$app->request->get('rwd_status');
        $code = Yii::$app->request->get('rwd_code');
        $daterange = Yii::$app->request->get('rwd_daterange');
        $country = Yii::$app->request->get('acc_cty_id');

        if (!empty($username)){
            $this->andFilterWhere(['like', 'acc_screen_name', $username]);
        }
        if (!empty($msisdn))
            $this->andFilterWhere(['like', 'rdr_msisdn', $msisdn]);
        if ($status == 'open' || $status == 'close')
            $this->andWhere('rdr_status = :status', [
                ':status' => $status
                ]);
        if (!empty($daterange)) {
            $daterange = explode(' to ', $daterange);
            $this->andWhere("FROM_UNIXTIME(rdr_datetime) BETWEEN '$daterange[0] 00:00:00' AND '$daterange[1] 23:59:59'");
        }
        if ($code)
            $this->andFilterWhere(['like','rdr_vod_code',$code]);

        if($country)
        {
            if($country == 'ID' || $country == 'MY')
            {
                $this->andWhere(['acc_cty_id'=> $country]);
            }
        }

        $this->join('LEFT JOIN','tbl_account','tbl_account.acc_id = rdr_acc_id');
        $this->orderBy('rdr_datetime DESC');
        return $this;
    }   

    public function getExcelColumns()
    {
        return  [
            'A' => [
                'name' => 'Username',
                'width' => 30,
                'height' => 5,
                'db_column' => 'account',
                'have_relations' => true,
                'relation_name' => 'acc_screen_name'
            ], 
            'B' => [
                'name' => 'Transaction Time',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_datetime',
                'format' => function($data) {
                    return Yii::$app->formatter->asDatetime(Utc::convert($data));
                }
            ], 
            'C' => [
                'name' => 'Name',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_name',
            ], 
            'D' => [
                'name' => 'MSISDN',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_msisdn',
            ],
            'E' => [
                'name' => 'Reference Code',
                'width' => 100,
                'height' => 5,
                'db_column' => 'rdr_reference_code',
            ],
            'F' => [
                'name' => 'SN',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_vod_sn',
            ],
            'G' => [
                'name' => 'Code',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_vod_code',
            ],
            'H' => [
                'name' => 'Point',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_vou_value',
            ],
            'I' => [
                'name' => 'Status',
                'width' => 30,
                'height' => 5,
                'db_column' => 'rdr_status',
            ]
        ];
    }

    public function getExcelColumnsStyles()
    {
        return [
            'font' => [
                 'bold'  => true,
            ]
        ];
    }
}
