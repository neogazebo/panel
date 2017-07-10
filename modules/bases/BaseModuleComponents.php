<?php

    namespace app\modules\bases;

    use app\models\AuthItem;

    abstract class BaseModuleComponents
    {
        protected $name;
        protected $components;

        abstract public function registerComponents();

        public function getComponents()
        {
            return $this->components;
        }

        public function getName()
        {
            return $this->name;
        }
    }
