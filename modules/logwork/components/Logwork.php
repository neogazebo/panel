<?php

namespace app\modules\logwork\components;

use app\modules\bases\BaseModuleComponents;
use app\models\AuthItem;

class Logwork extends BaseModuleComponents
{
    public function __construct()
    {
        $this->name = 'Logwork';
        $this->registerComponents();
    }

    public function registerComponents()
    {
        $this->components = [
            'Menus' => [

            ],
            'Pages' => [

            ],
            'Page_Components' => [
                // Working Hours Components
                [
                    'Name' => 'point_working_hours',
                    'Type' => 'page_components',
                    'Sub_Type' => 'form_button',
                    'Description' => 'Working Hours Point Button (Index Page)',
                    'Origin' => 'Index Page',
                    'Permission' => AuthItem::find()->getPermissions($this->name, "Logwork[Page_Components][point_working_hours]")
                ],
                // End Correction Page components
            ]
        ];
    }
}
