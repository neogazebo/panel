<?php

    namespace app\modules\system\processors\merchant_hq;

    use Yii;

    use app\modules\system\processors\bases\BaseProcessor;

    use app\models\Company;

    class MerchantHqChildrenSaveProcessor extends BaseProcessor
    {
        public function process()
        {
            $transaction = Yii::$app->db->beginTransaction();

            try
            {
                $parent_id = Yii::$app->request->post('com_id');
                $children = Yii::$app->request->post('children');
                $op_confirmation = Yii::$app->request->post('op_confirmation');
                $merchant_children = Company::find()->getChildMerchants($parent_id)->all();
                $merchant_children_id = Company::find()->getChildMerchantsId($parent_id);

                // if parent don't have any children yet
                if(!$merchant_children)
                {
                    $children_names = Company::find()->getChildrenNames($children);

                    // if children post data is empty
                    if(!$children)
                    {
                        throw new \Exception("Please fill the merchants data", self::SYSTEM_ERROR_CODE);
                    }
                    
                    // if user have not confirm the operation yet
                    if(!$op_confirmation)
                    {
                        $message = Yii::$app->controller->renderPartial('partials/child_op', ['data' => $children_names, 'op' => 'add']);
                        return $this->json_helper->jsonOutput(self::CHILDREN_OP_CONFIRMATION_CODE, 'error', $message);
                    }

                    Company::find()->saveMerchantChildren($parent_id, $children);
                    $transaction->commit();
                    return $this->json_helper->jsonOutput(0, 'success', null);
                }

                // if parent already have children
                if($merchant_children)
                {
                    // if children post data is empty
                    if(!$children)
                    {
                        if(!$op_confirmation)
                        {
                            return $this->json_helper->jsonOutput(self::CHILDREN_OP_CONFIRMATION_CODE, 'error', 'You are about to empty the data');
                        }

                        Company::find()->saveMerchantChildren(0, $merchant_children_id);
                        $transaction->commit();
                        return $this->json_helper->jsonOutput(0, 'success', null);
                    }

                    // if children post data is not empty
                    // it's an add or remove op?

                    // calculate the differences between posted data and db data
                    $changes = array_merge(array_diff($children, $merchant_children_id), array_diff($merchant_children_id, $children));
                    
                    if($changes)
                    {
                        // check also removed data
                        $removed = array_diff($merchant_children_id, $children);
                        $removed_children_names = Company::find()->getChildrenNames($removed);

                        if($removed)
                        {
                            foreach($changes as $key => $change)
                            {
                                if(in_array($change, $removed))
                                {
                                    unset($changes[$key]);
                                }
                            }
                        }

                        $children_names = Company::find()->getChildrenNames($changes);

                        if(!$op_confirmation)
                        {
                            if(!$removed)
                            {
                                $removed_children_names = null;
                            }
                            
                            $message = Yii::$app->controller->renderPartial('partials/child_op_combo', [
                                'added_merchants' => $children_names, 
                                'removed_merchants' => $removed_children_names, 
                            ]);

                            return $this->json_helper->jsonOutput(self::CHILDREN_OP_CONFIRMATION_CODE, 'error', $message);
                        }

                        Company::find()->saveMerchantChildren($parent_id, $changes);

                        if($removed)
                        {
                            Company::find()->saveMerchantChildren(0, $removed);
                        }

                        $transaction->commit();
                        return $this->json_helper->jsonOutput(0, 'success', null);
                    }
                    else
                    {
                        throw new \Exception("No changes were made, so no operation will be performed.", 1000);
                    }
                }
            }
            catch(\Exception $e)
            {
                $transaction->rollback();
                return $this->json_helper->jsonOutput(1000, 'error', $e->getMessage());
            }

            
        }
    }
