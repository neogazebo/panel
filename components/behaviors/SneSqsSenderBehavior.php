<?php

    namespace app\components\behaviors;

    use Yii;

    use yii\db\ActiveRecord;
    use yii\base\Behavior;

    use app\models\SnapEarn;

    class SneSqsSenderBehavior extends Behavior
    {
        public $sne_model;
        public $origin;

        public function events()
        {
            return [
                ActiveRecord::EVENT_AFTER_UPDATE => 'sendSqsMessage',
            ];
        }

        public function sendSqsMessage($event)
        {
            $sna_id = $this->sne_model->sna_id;
            $yiipath = Yii::getAlias('@app/yii');

            $send_sqs_message = false;
            //$com_is_premium = ($this->sne_model->business->com_premium == 1 && $this->sne_model->business->com_speciality == 2);
            $com_is_premium = true;

            if( ($this->origin == 'default') && ($this->sne_model->sna_status == 1) && ($com_is_premium) )
            {
                $send_sqs_message = true;
            }
            else if( ($this->origin == 'correction') && ($this->sne_model->sna_status == 1 || $this->sne_model->sna_status == 2) && ($com_is_premium) )
            {
                $send_sqs_message = true;
            }

            if($send_sqs_message)
            {
                shell_exec("php $yiipath sne-sqs-sender -sna_id=$sna_id > /dev/null 2>/dev/null &");
            }
        }
    }