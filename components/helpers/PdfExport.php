<?php

namespace app\components\helpers;

use Yii;
use yii\helpers\Url;
use kartik\mpdf\Pdf;

class PdfExport
{
	public static function export($title, $model, $query, $preview, $snapearn = false)
	{
        $filename = Yii::$app->basePath . '/web/runtime/' . uniqid() . '.csv';

        // create pdf
        $download_is = Pdf::DEST_BROWSER;
        if ($model->download_is == 1) {
            $download_is = Pdf::DEST_DOWNLOAD;
        }

        if ($snapearn == false) {
            if($query->count() >= 1024) {
                $result = [
                    'message' => 'Unable to print file, total data is too large. Please choose shorter <strong>Date range</strong>.',
                ];
                return $result;
            }
            $query = $query->asArray()->all();
        }

        $username = $title['username'];
        $country = $title['country'];
        $first_date = $title['first_date'];
        $last_date = $title['last_date'];
        $brand = $title['brand'];

        $content = Yii::$app->controller->renderPartial($preview, [
            'username' => $username,
            'country' => $country,
            'first_date' => $first_date,
            'last_date' => $last_date,
            'snapearn' => $snapearn,
            'report' => $query
        ]);

        // preview in pdf
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $download_is,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
//            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@web/themes/AdminLTE/dist/css/AdminLTE.css',
            // any css to be embedded if required
            'cssInline' => "
                body {
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
                    font-weight: 400;
                    overflow-x: hidden;
                    overflow-y: auto;
                }
                html, body {
                    min-height: 100%;
                }
                body {
                    font-family: 'Helvetica Neue';
                    font-size: 9pt;
                    line-height: 1.42857143;
                    color: #333;
                    background-color: #fff;
                }
                page-header {
                    margin: 10px 0 20px 0;
                    font-size: 22px;
                }
                table {
                    border-collapse: collapse;
                    width: 100%;
                }

                th, td {
                    text-align: left;
                    padding: 8px;
                }

                tr:nth-child(even){background-color: #f2f2f2}

                th {
                    background-color: #00c0ef;
                    color: white;
                    font-weight : normal;
                }
                
                tfoot > tr {
                    background-color: #777;
                    color: white;
                    font-weight : normal;
                }
                
                .no-shadow {
                    box-shadow: none !important;
                }
                .well-sm {
                    padding: 9px;
                    border-radius: 3px;
                }
                .well {
                    min-height: 20px;
                    padding: 19px;
                    margin-bottom: 20px;
                    background-color: #f5f5f5;
                    border: 1px solid #e3e3e3;
                    border-radius: 4px;
                    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                }
                .text-muted {
                    color: #777;
                }
                p {
                    margin: 0 0 10px;
                }
                .col-sm-4 {
                    width: 44,33333%;
                }
            ",
             // set mPDF properties on the fly
            // 'options' => ['title' => $title],
             // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [
                    $brand.'|| '.$username.'/Report/{DATE Y/m/j/H:m:s}'
                ],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
	}
}