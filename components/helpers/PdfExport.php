<?php

namespace app\components\helpers;

use Yii;
use kartik\mpdf\Pdf;

class PdfExport
{
	public static function export($title, $model, $query, $preview, $redirect, $snapearn = false)
	{
        $filename = Yii::$app->basePath . '/web/runtime/' . uniqid() . '.csv';

        // create pdf
        $download_is = Pdf::DEST_BROWSER;
        if ($model->download_is == 1) {
            $download_is = Pdf::DEST_DOWNLOAD;
        }

        if ($snapearn == false) {
            if($query->count() >= 1000) {
                $this->setMessage('save', 'error', '
                    Unable to print file, total data is too large. Please choose shorter <strong>Date range</strong>.
                ');
                return $this->redirect([$redirect]);
            }
            $query = $query->all();
        }

        $username = $title['username'];
        $country = $title['country'];
        $first_date = $title['first_date'];
        $last_date = $title['last_date'];

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
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '
                body {
                    margin: 0;
                    padding: 0;
                    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.42857143;
                    color: #333;
                    background-color: #fff
                }
                h3 {
                    font-size: 20px;
                    font-weight: 500;
                    line-height: 1.1;
                    color: inherit
                }
                table {
                    background-color: transparent;
                    border-spacing: 0;
                    border-collapse: collapse;
                }
                th {
                    text-align: left
                }
                .panel {
                    margin-bottom: 20px;
                    background-color: #fff;
                    border: 1px solid transparent;
                    border-radius: 4px;
                    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
                    box-shadow: 0 1px 1px rgba(0, 0, 0, .05)
                }
                .panel-default {
                    border-color: #ddd
                }
                .panel-default > .panel-heading {
                    color: #333;
                    background-color: #f5f5f5;
                    border-color: #ddd;
                }
                .panel-heading {
                    padding: 10px 15px;
                    border-bottom: 1px solid transparent;
                    border-top-left-radius: 3px;
                    border-top-right-radius: 3px
                }
                .panel > .table-responsive {
                    margin-bottom: 0;
                    border: 0
                }
                .panel-body {
                    padding: 15px
                }
                .table-responsive {
                    min-height: .01%;
                    overflow-x: auto
                }
                .panel > .table-responsive:last-child > .table:last-child, .panel > .table:last-child {
                    border-bottom-right-radius: 3px;
                    border-bottom-left-radius: 3px;
                }
                .panel > .panel-collapse > .table, .panel > .table, .panel > .table-responsive > .table {
                    margin-bottom: 0
                }
                .table {
                    width: 100%;
                    max-width: 100%;
                    margin-bottom: 20px
                }
                .table > thead > tr > th {
                    vertical-align: bottom;
                    border-bottom: 2px solid #ddd
                }
                .table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
                    padding: 5px
                }
                .table-striped > tbody > tr:nth-of-type(odd) {
                    background-color: #f9f9f9
                }
            ',
             // set mPDF properties on the fly
            // 'options' => ['title' => $title],
             // call mPDF methods on the fly
            'methods' => [
                // 'SetHeader' => [$title],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        // return the pdf output as per the destination setting
        return $pdf->render();
	}
}