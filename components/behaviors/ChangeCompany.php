<?php

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\models\ChangeLog;
use app\models\User;

class ChangeCompany extends Behavior {

    public $events = [
        ActiveRecord::EVENT_BEFORE_UPDATE,
    ];

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'update',
        ];
    }

    public function update($event) {
        if ($this->owner->oldAttributes['com_email'] !== $this->owner->com_email) {
            
            ChangeLog::set($this->owner->com_usr_id, 'com_email', $this->owner->oldAttributes['com_email'], $this->owner->com_email);
            
            $user = User::findOne($this->owner->com_usr_id);
            $user->usr_email = $this->owner->com_email;
            $user->save(false);
        }
    }

}
