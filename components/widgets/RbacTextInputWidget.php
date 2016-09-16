<?php

	namespace app\components\widgets;

	use Yii;

	use yii\base\Widget;

	use app\components\widgets\traits\ClassProcessorTrait;

	class RbacTextInputWidget extends Widget
	{
		use ClassProcessorTrait;

		public $label;
		public $name;
		public $id;
		public $class_names;
		public $value;
		public $placeholder;
		public $permission;
		
		public function init()
		{
			parent::init();
			$this->class_names = $this->processClass($this->class_names);
		}
		
		public function run()
		{
			$view = Yii::$app->permission_helper->processPermissions($this->permission['module'], $this->permission['name']);

			if($view)
			{
				return $this->render('rbac_text_input',[
					'label' => $this->label,
					'name' => $this->name,
					'id' => $this->id,
					'class' => $this->class_names,
					'value' => $this->value,
					'permission' => $this->permission,
					'placeholder' => $this->placeholder,
					'view' => $view
				]);
			}
		}
	}