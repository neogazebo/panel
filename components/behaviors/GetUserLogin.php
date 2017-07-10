<?php
namespace app\components\behaviors;

use yii\base\Behavior;

/**
* Author : ilham Fauzi
* Mail   : ilham@ebizu.com
*/

class GetUserLogin extends Behavior
{	
	public $event = [
		ActiveRecord::EVENT_BEFORE_INSERT,
		ActiveRecord::EVENT_BEFORE_UPDATE,
	];

	public function events()
	{
		foreach ($events as $event) {
            $events[$event] = 'explodeLoc';
        }
        return $events;
	}

	public function insert($event)
	{
		
	}
}
