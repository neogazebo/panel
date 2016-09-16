<?php

	namespace app\components\widgets;

	use yii\base\Widget;

	abstract class BaseRbacWidget extends Widget
	{
		protected $label;
		protected $name;
		protected $class_names;
		protected $permission;
		
		public function init()
		{
			parent::init();
		}
	}