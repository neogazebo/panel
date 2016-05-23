<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class UsersQuery extends ActiveQuery
{
    public function getList()
    {
        if (!empty($_GET['search'])) {
            $this->andWhere('
                username LIKE :get OR email LIKE :get
            ', [':get' => '%' . $_GET['search'] . '%']);
        }
        if (!empty($_GET['type'])) {
            if($_GET['type'] != 'ALL') {
                $this->andWhere('
                    type = :type
                ', [':type' => $_GET['type']]);
            }
        }
        if (!empty($_GET['country'])) {
            if($_GET['country'] != 'ALL') {
                $this->andWhere('
                    country = :country
                ', [':country' => $_GET['country']]);
            }
        }
        if(!empty($_GET['daterange'])) {
            $daterange = explode(" to ", $_GET['daterange']);
            $timezone = 7;
            if(Yii::$app->user->identity->country == 'MYR')
                $timezone = 8;

            $this->andWhere('date(FROM_UNIXTIME(create_time + (3600 * '.$timezone.'))) BETWEEN :first AND :last',[
                ':first' => $daterange[0] . ' 00:00:00',
                ':last' => $daterange[1] . ' 23:59:59'
            ]);
        }

        if (Yii::$app->user->identity->type == AdminUser::TYPE_MALL) {
            $this->andWhere(['mall' => Yii::$app->user->identity->mall]);
        }
        return $this;
    }

}
