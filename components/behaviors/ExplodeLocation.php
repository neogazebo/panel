<?php

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\City;
use app\models\Region;
use app\models\Country;

class ExplodeLocation extends Behavior {

    public $location = null;
    public $attributes = [
        'city' => 'cit_id',
        'region' => 'reg_id',
        'country' => 'cny_id'
    ];
    public $events = [
        ActiveRecord::EVENT_BEFORE_INSERT,
        ActiveRecord::EVENT_BEFORE_UPDATE,
    ];

    public function events() {
        $events = $this->events;
        foreach ($events as $event) {
            $events[$event] = 'explodeLoc';
        }
        return $events;
    }

    public function ExplodeLoc($event) {
        $attributes = $this->attributes;
        $location = $this->location;
        $location = $this->owner->$location;
        if (!empty($location)) {
            $address = explode(', ', $location);
            if (count($address) == 3) {
                $addressIds = City::addressIds($address);
                $this->owner->{$attributes['city']} = $addressIds['cit_id'];
                $this->owner->{$attributes['region']} = $addressIds['reg_id'];
                $this->owner->{$attributes['country']} = $addressIds['cny_id'];
            }
        }
    }

}
