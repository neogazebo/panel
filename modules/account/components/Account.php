<?php

    namespace app\modules\account\components;

    use app\modules\bases\BaseModuleComponents;

    use app\models\AuthItem;

    class Account extends BaseModuleComponents
    {
        public function __construct()
        {
            $this->name = 'Account';
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
                    [
                        'Name' => 'block_user',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Block User Button (Detail Page)',
                        'Origin' => 'Detail Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Account[Page_Components][block_user]")
                    ],
                    [
                        'Name' => 'change_country',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Change Country Button (Detail Page)',
                        'Origin' => 'Detail Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Account[Page_Components][change_country]")
                    ],
                    [
                        'Name' => 'point_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Point Correction Button (Detail Page)',
                        'Origin' => 'Detail Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Account[Page_Components][point_correction]")
                    ],
                ]
            ];
        }
    }
