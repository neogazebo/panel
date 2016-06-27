<?php

namespace app\models;

use Yii;
/**
 * This is the ActiveQuery class for [[City]].
 *
 * @see City
 */
class CityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return City[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return City|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function SearchCityList()
    {
        $q = $_GET['q'];
        $query = "
                SELECT a.cit_id, a.cit_name, b.reg_id, b.reg_name, c.cny_id, c.cny_name
                FROM tbl_city a, tbl_region b, tbl_country c
                WHERE a.cit_region_id = b.reg_id
                    AND b.reg_country_id = c.cny_id
                    AND a.cit_name LIKE '%" . $q . "%'
                ORDER BY a.cit_name
                LIMIT 10";
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        $return = [];
        foreach ($query as $row) {
            $return[]['value'] = $row['cit_name'] . ', ' . $row['reg_name'] . ', ' . $row['cny_name'];
        }
        return $return;
    }

}
