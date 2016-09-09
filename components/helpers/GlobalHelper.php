<?php

namespace app\components\helpers;

/**
 * GlobalHelper containing common global functionality 
 *
 * @author tajhul <tajhul@ebizu.com>
 * @since V2.1.9
 */

use yii\base\Component;
use Yii;
use yii\base\InvalidConfigException;

class GlobalHelper extends Component
{
    /**
     * Checking status of GST applicable is Enabled or not for current logged-in company
     * @return bool (gst status)
     */
    public function getCompanyGSTEnabled()
    {
        return Yii::$app->loggedin->company->com_gst_enabled == 1 ? true : false;       
    }
    
    /*
    * Download data as Csv file
    */    
    public function downloadAsCSV($data, $filename = null) {
        $file = new GFile();
        return $file->generateCSV($data, $filename);
    }

/**
* Author : ilham Fauzi
* Mail   : ilham@ebizu.com
*/

    public static function Weekdays()
    {
        $weekdays = [
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday'
        ];
        return $weekdays;
    }

    public static function TempCountry()
    {
        $country = [
            'IDR' => 'Indonesia',
            'MYR' => 'Malaysia'
        ];
        return $country;
    }  
}


/**
* Helper for file operation
*/
class GFile {
    public function generateCSV($data, $filename = null) {
        if ($filename === null)
            $filename = 'export-' . time() . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        self::outputCSV($data);
    }

    private function outputCSV($data) {
        $output = fopen("php://output", "w");
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }       
    
    /**
     * trims text to a space then adds ellipses if desired
     * @param string $path full path file-nya
     * @param int $speed download speed
     * @return stream file 
     */
    public static function Download($path, $speed = null) {
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
}
   
