<?php

namespace app\components\helpers;

use Yii;

/**
 * Containing common helpers function
 *
 * @author Tajhul Faijin <tajhul@ebizu.com>
 */
class General
{
    /**
     * trims text to a space then adds ellipses if desired
     * @param string $input text to trim
     * @param int $length in characters to trim to
     * @param bool $ellipses if ellipses (...) are to be added
     * @param bool $strip_html if html tags are to be stripped
     * @return string 
     */
    public static function trim_text($input, $length, $ellipses = true, $strip_html = true)
    {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

    /**
     * trims text to a space then adds ellipses if desired
     * @param string $path full path file-nya
     * @param int $speed download speed
     * @return stream file 
     */
    public static function Download($path, $speed = null)
    {
        if (is_file($path) === true) {
            $file = @fopen($path, 'rb');
            $speed = (isset($speed) === true) ? round($speed * 1024) : 524288;

            if (is_resource($file) === true) {
                set_time_limit(0);
                ignore_user_abort(false);

                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                header('Expires: 0');
                header('Pragma: public');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Type: application/octet-stream');
                header('Content-Length: ' . sprintf('%u', filesize($path)));
                header('Content-Disposition: attachment; filename="' . basename($path) . '"');
                header('Content-Transfer-Encoding: binary');

                while (feof($file) !== true) {
                    echo fread($file, $speed);

                    while (ob_get_level() > 0) {
                        ob_end_flush();
                    }

                    flush();
                    sleep(1);
                }

                fclose($file);
            }

            exit();
        }

        return false;
    }

    public static function downloadCSV($data, $filename = null)
    {
        if ($filename === null)
            $filename = 'export-' . time() . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        self::outputCSV($data);
    }

    public static function outputCSV($data)
    {
        $output = fopen("php://output", "w");
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }

    public static function generateUUID()
    {
        $con = Yii::$app->db;
        $q = $con->createCommand('SELECT uuid() as UUID')->queryOne();
        return $q['UUID'];
    }

    /*
     * Extract error messages from model
     * @param : error object
     * return string
     */
    public static function extractErrorModel($errors, $showIndex = false)
    {
        $errorMessages = '';
        $errorBase = $errors;
        foreach ($errors as $k => $error) {
            if($showIndex === true)
                $errorMessages .= $k . ': - ' . $errorBase[$k][0] . '<br/>';
            else
                $errorMessages .= '- ' . $errorBase[$k][0] . '<br/>';
        }
        return $errorMessages;
    }

    /**
     * Check remote file exist
     * @param : URL file
     */
    public static function checkRemoteFileExist($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (curl_exec($ch) !== FALSE) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function generateRandomString($length = 10)
    {
        $rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" . time() .  uniqid()), 0, $length);
        return strtoupper($rand);
    }    

}
