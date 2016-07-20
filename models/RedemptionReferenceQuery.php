<?php

namespace app\models;

use Yii;

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
        // $userId = Yii::$app->request->get('rwd_params');
        // $msisdn = Yii::$app->request->get('rwd_msisdn');
        // $status = Yii::$app->request->get('rwd_status');
        // $code = Yii::$app->request->get('rwd_code');
        // $daterange = Yii::$app->request->get('rwd_daterange');
        // if (!empty($userId))
        //     $this->andFilterWhere(['like', 'rdr_acc_id', $userId]);
        // if (!empty($msisdn))
        //     $this->andFilterWhere(['like', 'rdr_msisdn', $msisdn]);
        // if (!empty($status))
        //     $this->andFilterWhere(['like', 'rdr_status', $status]);
        // if (!empty($daterange))
        //     $daterange = explode(' to ', $daterange);
        //     $this->andFilterWhere("FROM_UNIXTIME(rdr_datetime) BETWEEN '$daterange[0] 00:00:00' AND '$daterange[1] 23:59:59'");
        // if (!empty($code))
        //     $this->andFilterWhere(['like','rdr_vod_code',$code]);

        // $this->orderBy('rdr_datetime DESC');

        // return $this;
    }   
}
