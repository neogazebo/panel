<?php

namespace common\models;

use Yii;

/**
 *
 * @author febri@ebizu.com
 */
class AuditReportQuery extends \yii\db\ActiveQuery
{
    public function getList()
    {
        $this->leftJoin('tbl_admin_user c', 'c.id = arp_usr_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                arp_activity LIKE :get OR 
                arp_changed_attributes LIKE :get OR 
                c.username LIKE :get 
            ', [
                ':get' => '%' . $_GET['search'] . '%'
            ]);
        }
        return $this;
    }
    
    public function allUser()
    {
        if(Yii::$app->user->identity->type == 3)
        {    
            $this->andWhere('c.mall = :mall ',[
                ':mall' => Yii::$app->user->identity->mall
            ]);
        }
        
        return $this;
    }
    
    /**
     * Filtered only my own data
     */
    public function getMyOwn(){
        $this->andWhere('arp_usr_id=:id',['id'=>Yii::$app->user->id]);
        return $this;
    }

    public function getMyProduct($id = null){
        
        $this->andWhere('arp_item=:item_model',[
            'item_model' => 'tbl_product_item',
        ]);
        
        // filter by specific product
        if($id !== null){
            $this->andWhere('arp_item_id=:item_id',[
                'item_id' => $id,
            ]);
        }
        $this->orderBy('arp_datetime DESC');
        
        return $this;
    }
    
    public function getReportUser($data)
    {
        $this->select('username AS USERNAME, arp_activity as ACTIVITY,arp_changed_attributes as CHANGE,FROM_UNIXTIME(arp_datetime) as DATETIME');
        $this->leftJoin('tbl_admin_user','id = arp_usr_id');
        $this->andWhere($data['html'].' arp_usr_id = :id',[
            ':id'=>$data['username']
        ]);
        $this->andWhere('arp_datetime > UNIX_TIMESTAMP(DATE_FORMAT(STR_TO_DATE("' . $data['first_date'] . '", "%m/%d/%Y"), "%Y-%m-%d"))');
        $this->andWhere('arp_datetime <= UNIX_TIMESTAMP(DATE_FORMAT(STR_TO_DATE("' . $data['last_date'] . '", "%m/%d/%Y"), "%Y-%m-%d"))');
        $this->orderBy('arp_id '.$data['order']);
        $this->limit(100);
        return $this;
    }
    
}
