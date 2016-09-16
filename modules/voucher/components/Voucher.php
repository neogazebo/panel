<?php

    namespace app\modules\voucher\components;

    use app\modules\bases\BaseModuleComponents;

    use app\models\AuthItem;

    class Voucher extends BaseModuleComponents
    {
        public function __construct()
        {
            $this->name = 'Voucher';
            $this->registerComponents();
        }

        public function registerComponents()
        {
            $this->components = [

                'Menus' => [
                    [
                        'Name' => 'redemption',
                        'Type' => 'main_menu',
                        'Description' => 'Main menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Voucher[Menus][redemption]")
                    ],
                    [
                        'Name' => 'reward',
                        'Type' => 'sub_menu',
                        'Description' => 'Reward menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Voucher[Menus][reward]")
                    ],
                    [
                        'Name' => 'cash_vouchers',
                        'Type' => 'sub_menu',
                        'Description' => 'Cash Vouchers menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Voucher[Menus][cash_vouchers]")
                    ],
                ],

                'Pages' => [

                ],

                'Page_Components' => [
                    
                ]
            ];
        }
    }
