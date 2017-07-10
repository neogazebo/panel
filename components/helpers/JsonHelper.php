<?php

    namespace app\components\helpers;

    use Yii;

    class JsonHelper
    {
    	public function jsonOutput($code, $status, $message, $data = null)
        {
            return [
                'error' => $code,
                'status' => $status,
                'message' => $message,
                'data' => $data
            ];
        }
    }