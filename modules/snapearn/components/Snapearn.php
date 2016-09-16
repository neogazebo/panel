<?php

    namespace app\modules\snapearn\components;

    use app\modules\bases\BaseModuleComponents;

    use app\models\AuthItem;

    class Snapearn extends BaseModuleComponents
    {
        public function __construct()
        {
            $this->name = 'Snapearn';
            $this->registerComponents();
        }

        public function registerComponents()
        {
            $this->components = [

                'Menus' => [
                    [
                        'Name' => 'snapearn',
                        'Type' => 'main_menu',
                        'Description' => 'Main menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Menus][snapearn]")
                    ],
                    [
                        'Name' => 'list',
                        'Type' => 'sub_menu',
                        'Description' => 'List menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Menus][list]")
                    ],
                    [
                        'Name' => 'working_hours',
                        'Type' => 'sub_menu',
                        'Description' => 'Working Hours menu',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Menus][working_hours]")
                    ],
                ],

                'Pages' => [
                    [
                        'Name' => 'list',
                        'Type' => 'page',
                        'Description' => 'List page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Pages][list]")
                    ],
                    [
                        'Name' => 'update',
                        'Type' => 'page',
                        'Description' => 'Update page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Pages][update]")
                    ],
                    [
                        'Name' => 'correction',
                        'Type' => 'page',
                        'Description' => 'Correction page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Pages][correction]")
                    ]
                ],

                'Page_Components' => [
                    // Search Form (List Page)
                    [
                        'Name' => 'country',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][country]")
                    ],
                    [
                        'Name' => 'receipt_status',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][receipt_status]")
                    ],
                    [
                        'Name' => 'date_range',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][date_range]")
                    ],
                    [
                        'Name' => 'receipt_number',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][receipt_number]")
                    ],
                    [
                        'Name' => 'member_field',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][member_field]")
                    ],
                    [
                        'Name' => 'member_email',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][member_email]")
                    ],
                    [
                        'Name' => 'operator',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][operator]")
                    ],
                    [
                        'Name' => 'merchant_field',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][merchant_field]")
                    ],
                    [
                        'Name' => 'submit_button',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][submit_button]")
                    ],
                    [
                        'Name' => 'export_button',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Search Form (List Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][export_button]")
                    ],
                    // End Search Form (List Page)

                    // List Table (List Page)
                    [
                        'Name' => 'merchant',
                        'Type' => 'page_components',
                        'Sub_Type' => 'table_column',
                        'Description' => 'Test',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][merchant]")
                    ],
                    [
                        'Name' => 'member',
                        'Type' => 'page_components',
                        'Sub_Type' => 'table_column',
                        'Description' => 'Test',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][member]")
                    ],
                    [
                        'Name' => 'actions',
                        'Type' => 'page_components',
                        'Sub_Type' => 'table_column',
                        'Description' => 'Test',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][actions]")
                    ],
                    // End List Table

                    // Update Page components
                    [
                        'Name' => 'add_new_merchant_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_new_merchant_update]")
                    ],
                    [
                        'Name' => 'add_existing_merchant_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_existing_merchant_update]")
                    ],
                    [
                        'Name' => 'add_point_button_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_point_button_update]")
                    ],
                    [
                        'Name' => 'status_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][status_update]")
                    ],
                    [
                        'Name' => 'transaction_time_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][transaction_time_update]")
                    ],
                    [
                        'Name' => 'ops_number_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][ops_number_update]")
                    ],
                    [
                        'Name' => 'amount_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][amount_update]")
                    ],
                    [
                        'Name' => 'point_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][point_update]")
                    ],
                    [
                        'Name' => 'reject_remark_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][reject_remark_update]")
                    ],
                    [
                        'Name' => 'push_notification_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][push_notification_update]")
                    ],
                    [
                        'Name' => 'save_button_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][save_button_update]")
                    ],
                    [
                        'Name' => 'save_next_button_update',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Update Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][save_next_button_update]")
                    ],
                    // End Update Page components

                    // Correction Page components
                    [
                        'Name' => 'add_new_merchant_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_new_merchant_correction]")
                    ],
                    [
                        'Name' => 'add_existing_merchant_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_existing_merchant_correction]")
                    ],
                    [
                        'Name' => 'add_point_button_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][add_point_button_correction]")
                    ],
                    [
                        'Name' => 'status_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][status_correction]")
                    ],
                    [
                        'Name' => 'transaction_time_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][transaction_time_correction]")
                    ],
                    [
                        'Name' => 'ops_number_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][ops_number_correction]")
                    ],
                    [
                        'Name' => 'amount_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][amount_correction]")
                    ],
                    [
                        'Name' => 'point_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][point_correction]")
                    ],
                    [
                        'Name' => 'reject_remark_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][reject_remark_correction]")
                    ],
                    [
                        'Name' => 'push_notification_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_field',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][push_notification_correction]")
                    ],
                    [
                        'Name' => 'save_button_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][save_button_correction]")
                    ],
                    [
                        'Name' => 'save_next_button_correction',
                        'Type' => 'page_components',
                        'Sub_Type' => 'form_button',
                        'Description' => 'Form Approval (Correction Page)',
                        'Origin' => 'List Page',
                        'Permission' => AuthItem::find()->getPermissions($this->name, "Snapearn[Page_Components][save_next_button_correction]")
                    ],
                    // End Correction Page components
                ]
            ];
        }
    }
