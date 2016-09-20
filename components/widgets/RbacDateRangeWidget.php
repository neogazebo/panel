<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\components\widgets\traits\ClassProcessorTrait;

class RbacDateRangeWidget extends Widget
{
	use ClassProcessorTrait;

	public $label;
	public $name;
	public $id;
	public $class_names;
	public $value;
	public $permission;
	
	public function init()
	{
		parent::init();
		$this->class_names = $this->processClass($this->class_names);
	}
	
	public function run()
	{
		$view = Yii::$app->permission_helper->processPermissions($this->permission['module'], $this->permission['name']);

		if ($view) {
			return $this->render('rbac_daterange',[
				'label' => $this->label,
				'name' => $this->name,
				'id' => $this->id,
				'class' => $this->class_names,
				'value' => $this->value,
				'permission' => $this->permission,
				'view' => $view
			]);
		}
	}
}