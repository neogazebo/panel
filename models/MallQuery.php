<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Mall]].
 *
 * @see Mall
 */
class MallQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Mall[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Mall|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getList()
    {
        if(isset($_GET['search'])) {
            $this->andWhere('
                mal_name LIKE :get OR 
                mal_description LIKE :get OR 
                mal_email LIKE :get OR 
                mal_address LIKE :get OR 
                mal_city LIKE :get OR 
                mal_building_id LIKE :get 
            ', [':get' => '%'.$_GET['search'].'%']);
        }
        if(isset($_GET['country'])) {
            if($_GET['country'] == 'MY')
                $this->andWhere('mal_country_id = :cny', [':cny' => 128]);
            elseif($_GET['country'] == 'ID')
                $this->andWhere('mal_country_id = :cny', [':cny' => 90]);
        }
        return $this;
    }

    public function getBeacon()
    {
        $this->select('mal_id, mal_name, mal_address, (
            SELECT COUNT(x.hac_id) FROM tbl_hardware_company x WHERE x.hac_mal_id = mal_id
        ) AS total');
        if(isset($_GET['search'])) {
            $this->andWhere(['LIKE', 'mal_name', $_GET['search']]);
        }
        if(isset($_GET['country'])) {
            if($_GET['country'] == 'MY')
                $this->andWhere('mal_country_id = :cny', [':cny' => 128]);
            elseif($_GET['country'] == 'ID')
                $this->andWhere('mal_country_id = :cny', [':cny' => 90]);
        }
        return $this;
    }    

    public function SearchMallList()
    {
        $search = $_GET['q'];
        $keyword = preg_split("/[\s,]+/",$search);
        $this->select('mal_id, mal_name');
        foreach($keyword as $key){
            $this->andWhere('mal_name LIKE "%'.$key.'%" ');
        }
        $this->andWhere('mal_status = :status',[
                ':status' => 1
            ]);
        return $this->all();
    }
}
