<?php
/**
 * Push notification component
 * @author : tajhul<tajhul@ebizu.com>
 * @copyright : Ebizu Shn Bhd, Ebizu Prima Indonesia PT
 * @since : 2015
 */

namespace app\components\extentions;

use Yii;
use yii\base\Component;

class PushNotif extends Component
{
    private $push_id = null;
    private $message = '';
    private $data = null;

    public function pushID($pushID)
    {
        $this->push_id = $pushID;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getSendApns(){
        $service = new APNS();
        return $service->setInstance($this->push_id, $this->message, $this->data)->push();
    }

}

class APNS {
    /**
     * = = = = = = = = = = = = = = = = = = = = = = = = = = = =
     * Class wrapper for Apple Push Notification System <APNS>
     * = = = = = = = = = = = = = = = = = = = = = = = = = = = =
     * @author : tajhul <tajhul@ebizu.com>
     * @since : 2015
     */
    private $param;
    private $device_id;

    /**
     * setInstance
     * @params string $device_id <The client device ID>
     * @params string $message <Message that will be send to client>
     * @params mix $data <Additional information data>
    */
    public function setInstance($device_id, $message, $data){
        $data = is_array($data) ? json_encode($data) : $data;
        $param = [
            'data' => [
                'msg' =>$message,
                'data' =>$data,
            ],
        ];
        $this->param = json_encode($param);
        $this->device_id = array(array('push_id' => $device_id));
        return $this;
    }

    /**
    * Send notification
    * @return Array
        Array
        (
            [0] => Array
                (
                    [status] => ok
                 )

        )
     * If status key = ok, push notification successfully sent
     */
    public function push(){
        $request = $this->send($this->device_id, json_decode($this->param));
        return $request;
    }


    private function send($push_id_array, $params)
    {
        $return_data	= array();
        $apns_host 		= 'gateway.sandbox.push.apple.com';
        $apns_cert	 	= __DIR__ . '/certificates/apns_ck.pem';
        $apns_port 		= 2195;

        if(file_exists($apns_cert) === false)
            return 'Certificate file does not exist!';

        $stream_context 	= stream_context_create();
        stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
        stream_context_set_option($stream_context, 'ssl', 'passphrase', 'abc123');

        $apns 			= stream_socket_client('ssl://' . $apns_host . ':' . $apns_port, $error, $errorString, 2, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $stream_context);
        $payload['aps'] = array('alert' => substr($params->data->msg, 0, 200), 'badge' => 1, 'sound' => 'default');

        if(isset($params->data->data))
        {
            $payload['data'] = $params->data->data;
        }

        $output 		= json_encode($payload);

        for ($i = 0; $i < count($push_id_array); $i++)
        {
            $token 			= pack('H*', $push_id_array[$i]['push_id']);
            $apns_message 	= chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
            fwrite($apns, $apns_message);
            $return_data[$i]['status'] =  'ok';
            sleep(1);
        }

        @socket_close($apns);
        fclose($apns);

        return $return_data;
    }
}

class GCM {
    /**
     * = = = = = = = = = = = = = = = = = = = = = = = = = = = =
     * Class wrapper for Android Push Notification System <GCM>
     * = = = = = = = = = = = = = = = = = = = = = = = = = = = =
     * @author : tajhul <tajhul@ebizu.com>
     * @since : 2015
     */
    // @TODO : add GCM support
}