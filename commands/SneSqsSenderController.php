<?php

    namespace app\commands;

    use Yii;

    use yii\console\Controller;

    use app\models\SnapEarn;
    
    use app\components\extensions\aws\SqsClient;

    class SneSqsSenderController extends Controller
    {
        public $sna_id;
        
        public function options($actionID)
        {
            return ['sna_id'];
        }
        
        public function optionAliases()
        {
            return ['sna_id' => 'sna_id'];
        }
        
        public function actionIndex()
        {
            $new_data = [];

            $sne_model = SnapEarn::findOne($this->sna_id);

            $account_device = $sne_model->member->activeDevice();

            $data = [
                'id' => $sne_model->sna_id,
                'cus_id' => $sne_model->member->acc_id,
                'mem_id' => $sne_model->member->acc_mem_id,
                'com_id' => $sne_model->sna_com_id,
                'firstname' => $this->processName($sne_model->member->acc_screen_name, 'first_name'),
                'lastname' => $this->processName($sne_model->member->acc_screen_name, 'last_name'),
                'gender' => (string) $sne_model->member->acc_gender,
                'birthdate' => $sne_model->member->acc_birthdate,
                'location' => $sne_model->member->acc_address,
                'datetime' => $sne_model->member->acc_tmz_id,
                'device' => $account_device ? (string) $account_device->dvc_id : '',
                'phone_number' => $sne_model->member->acc_msisdn,
                'email' => $sne_model->member->acc_facebook_email,
                'photo' => $sne_model->member->acc_photo,
                'cty_id' => $sne_model->member->acc_cty_id,
                'type' => 'sne',
                'snapearn_status' => $sne_model->sna_status,
                'snapearn_receipt_amount' => $sne_model->sna_ops_receipt_amount,
                'snapearn_point' => $sne_model->sna_point,
                'snapearn_upload_time' => $sne_model->sna_point,
                'snapearn_review_date' => $sne_model->sna_review_date,
                'voucher_detail_id' => '',
                'voucher_id' => '',
                'voucher_bought_id' => '',
                'voucher_name' => '',
                'voucher_code' => '',
                'voucher_value' => '',
                'voucher_customer_spent' => '',
                'voucher_total_amount' => '',
                'voucher_transaction_time' => '',
                'created' => $sne_model->sna_upload_date,
            ];

            foreach($data as $key => $value)
            {
                array_push($new_data, $value);
            }

            $new_data_imploded = implode(', ', $new_data);

            //var_dump($new_data_imploded);
            //die;

            Yii::$app->sqs_client->sendQueueMessage(Yii::$app->params['RETAILER_SQS_URL'], $new_data_imploded);
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