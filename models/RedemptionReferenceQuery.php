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
        $username = Yii::$app->request->get('username');
        $msisdn = Yii::$app->request->get('rwd_msisdn');
        $status = Yii::$app->request->get('rwd_status');
        $code = Yii::$app->request->get('rwd_code');
        $daterange = Yii::$app->request->get('rwd_daterange');

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

        $this->join('LEFT JOIN','tbl_account','tbl_account.acc_id = rdr_acc_id');
        $this->orderBy('rdr_datetime DESC');
        return $this;
    }   
}
