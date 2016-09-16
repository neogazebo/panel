<?php

    namespace app\components\helpers;

    class ModuleComponentsHelper
    {
        private $module_components;
        private $module_names = [];
        private $components = [];

        public function __construct()
        {
            $this->module_components = require('../config/module_components.php');

            foreach($this->module_components as $component)
            {
                $mod_comp = new $component;
                array_push($this->module_names, $mod_comp->getName());
                $this->components[$mod_comp->getName()] = $mod_comp->getComponents();
            }
        }

        public function getModuleNames()
        {
            return $this->module_names;
        }

        public function getComponents($module)
        {
            return $this->components[$module];
        }
    }
