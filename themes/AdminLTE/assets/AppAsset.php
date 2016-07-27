<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\themes\AdminLTE\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'css/site.css',',
        'themes/AdminLTE/bootstrap/css/bootstrap.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        'themes/AdminLTE/dist/css/AdminLTE.min.css',
        'themes/AdminLTE/dist/css/skins/_all-skins.min.css',
        'themes/AdminLTE/plugins/iCheck/flat/blue.css',
        'themes/AdminLTE/plugins/morris/morris.css',
        'themes/AdminLTE/plugins/select2/select2.css',
        'themes/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'themes/AdminLTE/plugins/datepicker/datepicker3.css',
        'themes/AdminLTE/plugins/daterangepicker/daterangepicker-bs3.css',
        'themes/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'themes/AdminLTE/plugins/sweet-alert/sweetalert.css',
        'themes/AdminLTE/dist/css/custome.css',
        'themes/AdminLTE/css/custom.css',
    ];
    public $js = [
        'themes/AdminLTE/dist/plugins/jQueryui/jquery-ui.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
        // 'themes/AdminLTE/plugins/morris/morris.min.js',
        'themes/AdminLTE/plugins/chartjs/Chart.min.js',
        'themes/AdminLTE/plugins/iCheck/icheck.min.js',
        'themes/AdminLTE/plugins/sparkline/jquery.sparkline.min.js',
        'themes/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'themes/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'themes/AdminLTE/plugins/knob/jquery.knob.js',
        'themes/AdminLTE/plugins/select2/select2.full.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',
        'themes/AdminLTE/plugins/daterangepicker/daterangepicker.js',
        'themes/AdminLTE/plugins/datepicker/bootstrap-datepicker.js',
        'themes/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'themes/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',
        'themes/AdminLTE/plugins/fastclick/fastclick.min.js',
        'themes/AdminLTE/plugins/sweet-alert/sweetalert.min.js',
        'themes/AdminLTE/dist/js/app.min.js',
        // 'themes/AdminLTE/dist/js/pages/dashboard.js',
        'themes/AdminLTE/dist/js/demo.js',
        'themes/AdminLTE/dist/plugins/multi-select/multi-select.js',
        'themes/AdminLTE/dist/js/custome.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
