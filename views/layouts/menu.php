<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
<!--     <li>
        <a href="<?= Yii::$app->urlManager->createUrl('dashboard/index') ?>">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
            <span class="label label-primary pull-right"></span>
        </a>
    </li> -->
    <li class="treeview">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Epay</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/index') ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/buy') ?>"><i class="fa fa-circle-o"></i> Buy Online PIN</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/recon/index') ?>"><i class="fa fa-circle-o"></i> Report</a></li>
        </ul>
    </li>
</ul>
