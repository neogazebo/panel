<?php

    namespace app\components\behaviors;

    use Yii;

    use yii\db\ActiveRecord;
    use yii\base\Behavior;

    use app\models\SnapEarn;
    
    use app\components\extensions\aws\SqsClient;

    class SneSqsSenderBehavior extends Behavior
    {
        public $sne_model;

        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_UPDATE => 'sendSqsMessage',
            ];
        }

        public function sendSqsMessage($event)
        {
            if($this->sne_model->sna_status == 1 ||$this->sne_model->sna_status == 2)
            {
                $id = $this->sne_model->sna_id;
                //$yiipath = Yii::getAlias('@app/yii');
                //shell_exec("php $yiipath sne-sqs-sender -sna_id=$id > /dev/null 2>/dev/null &");

                $new_data = [];

                $sne_model = SnapEarn::findOne($id);

                $data = [
                    'id' => $sne_model->member->acc_id,
                    'cus_id' => $sne_model->member->acc_id,
                    'mem_id' => $sne_model->member->acc_mem_id,
                    'com_id' => $sne_model->sna_com_id,
                    'firstname' => $this->processName($sne_model->member->acc_screen_name, 'first_name'),
                    'lastname' => $this->processName($sne_model->member->acc_screen_name, 'last_name'),
                    'gender' => $sne_model->member->acc_gender,
                    'birthdate' => $sne_model->member->acc_birthdate,
                    'location' => $sne_model->member->acc_address,
                    'datetime' => $sne_model->member->acc_created_datetime,
                    'device' => '',
                    'phone_number' => '',
                    'email' => $sne_model->member->acc_facebook_email,
                    'gender' => '',
                    'photo' => '',
                    'cty_id' => '',
                    'type' => 'sne',
                    'snapearn_status' => '',
                    'snapearn_receipt_amount' => '',
                    'snapearn_point' => '',
                    'snapearn_upload_time' => '',
                    'snapearn_review_date' => '',
                    'voucher_detail_id' => '',
                    'voucher_id' => '',
                    'voucher_bought_id' => '',
                    'voucher_name' => '',
                    'voucher_code' => '',
                    'voucher_value' => '',
                    'voucher_customer_spent' => '',
                    'voucher_total_amount' => '',
                    'voucher_transaction_time' => '',
                    'created' => '',
                ];

                foreach($data as $key => $value)
                {
                    array_push($new_data, $value);
                }

                $new_data_imploded = implode(', ', $new_data);

                Yii::$app->sqs_client->sendQueueMessage(Yii::$app->params['RETAILER_SQS_URL'], $new_data_imploded);
            }
        }

        private function processName($fullname, $type)
        {
            $name = '';
            $name_exploded = explode(' ', $fullname);

            if($type == 'first_name')
            {
                $name = $name_exploded[0];
            }
            else
            {
                $last_names = [];

                $name = '';

                if(isset($name_exploded[1]))
                {
                    for($i=1;$i<count($name_exploded);$i++)
                    {
                        array_push($last_names, $name_exploded[$i]);
                    }

                    $name = implode(' ', $last_names);
                }
            }

            return $name;
        }
    }