<?php

	namespace app\components\widgets;

	use Yii;

	use yii\base\Widget;

	use app\components\widgets\traits\ClassProcessorTrait;

	class RbacButtonWidget extends Widget
	{
		use ClassProcessorTrait;

		public $label;
		public $text;
		public $name;
		public $id;
		public $class_names;
		public $value;
		public $icon;
		public $type;
		public $use_container;
		public $permission;
		
		public function init()
		{
			parent::init();
			$this->class_names = $this->processClass($this->class_names);
			$this->icon = $this->processClass($this->icon);
		}
		
		public function run()
		{
			$view = Yii::$app->permission_helper->processPermissions($this->permission['module'], $this->permission['name']);

			if($view)
			{
				return $this->render('rbac_button',[
					'label' => $this->label,
					'text' => $this->text,
					'name' => $this->name,
					'id' => $this->id,
					'class' => $this->class_names,
					'value' => $this->value,
					'permission' => $this->permission,
					'icon' => $this->icon,
					'type' => $this->type,
					'use_container' => $this->use_container,
					'view' => $view
				]);
			}
		}
	}