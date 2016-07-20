<?php

namespace app\models;

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
        // $userId = Yii::$app->request->isGet('rwd_params');
        // $msisdn = Yii::$app->request->isGet('rwd_msisdn');
        // $status = Yii::$app->request->isGet('rwd_status');
        // $code = Yii::$app->request->isGet('rwd_code');
        // $daterange = Yii::$app->request->isGet('rwd_daterange');
        // if (!empty($userId))
        //     $this->andFilterWhere(['like', 'rdr_acc_id', $userId]);
        // if (!empty($msisdn))
        //     $this->andFilterWhere(['like', 'rdr_msisdn', $msisdn]);
        // if (!empty($status))
        //     $this->andFilterWhere(['like', 'rdr_status', $status]);
        // if (!empty($daterange))
        //     $rdr_daterange = explode(' to ', ($daterange));
        //     $this->andFilterWhere(['between',''])
    }   
}
