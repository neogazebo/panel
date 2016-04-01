<?php

namespace common\models;

/**
 * This is the model class for tbl_audit_report
 * @property integer $arp_usr_id
 * @property integer $arp_item_id
 * @property string $arp_activity
 * @property string $arp_item
 * @property string $arp_changed_attributes
 * @property integer $arp_datetime
 *
 * @author febri@ebizu.com
 */
use yii\db\ActiveRecord;

class AuditReport extends EbizuActiveRecord
{
    // for activity report
    public $to;
    public $date;
    public $USERNAME;
    public $ACTIVITY;
    public $CHANGE;
    public $DATETIME;

    public static function find()
    {
        return new AuditReportQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'tbl_audit_report';
    }
    
    public function __construct($activity = '', $user_id = '', $item = '', $item_id = '', $changed_attr = [], $app = null)
    {
        $this->arp_usr_id = $user_id;
        $this->arp_ara_code = !empty($app) ? $app : null;
        $this->arp_activity = $activity;
        $this->arp_item = $item;
        $this->arp_item_id = $item_id;
        $this->arp_changed_attributes = !empty($changed_attr) ? implode('~ ', $this->array_map_assoc(function($k,$v){return "$k : $v";},$changed_attr)) : '';
    }

    public function rules()
    {
        return [
            [['arp_usr_id', 'arp_activity', 'arp_item', 'arp_item_id'], 'required'],
            [['arp_changed_attributes','arp_ara_code'], 'string'],
            [['arp_datetime'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['arp_datetime'],
                ],
            ],            
        ];
    }     
    
    /**
     * 
     * @param string $activity activity name
     * @param integer $user_id user whose doing the activity
     * @param string $item item / object name that being audited
     * @param integer $item_id object item id (primary key)
     * @param array $changed_attr changged attribute during activity
     * @param type $app
     * @return object
     */
    public static function setAuditReport($activity, $user_id, $item, $item_id, $changed_attr = [], $app = null)
    {
        return new self($activity, $user_id, $item, $item_id, $changed_attr, $app);
    }
    
    public function array_map_assoc($callback, $array)
    {
        $r = [];
        foreach ($array as $key => $value)
            $r[$key] = $callback($key, $value);
        return $r;
    }
    
    public function getUserAdmin()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'arp_usr_id']);
    }

    public function getTableList()
    {
        $model = $this->groupBy('arp_item');
        $item = [];
        foreach($model as $row) {
            $it = explode('/', $row->arp_item);
            $item[] = [
                end($it) => end($it)
            ];
        }
        return $item;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['usr_id' => 'arp_usr_id']);
    }    
    
    public function getProduct()
    {
        return $this->hasOne(ProductItem::className(), ['pit_id' => 'arp_item_id']);
    }        
    
    public function attributeLabels()
    {
        return [
            'arp_ara_code' => 'Application',
            'arp_activity' => 'Activity',
            'arp_changed_attributes' => 'Changed Attribute',
            'arp_datetime' => 'Activity Time'
        ];
    }
    
    public function getFormatedChangedAttribute()
    {
        $result = '';
        if(!empty($this->arp_changed_attributes))
        {
            $result = '- '.str_replace('~', '<br /> -', strip_tags($this->arp_changed_attributes));
        }
        
        return $result;
    }

}
