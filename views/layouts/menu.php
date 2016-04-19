<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li>
        <a href="<?= Yii::$app->urlManager->createUrl('partner/index') ?>">
            <i class="fa fa-th-list"></i>
            <span>Partner</span>
            <span class="label label-primary pull-right"></span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('partner/index') ?>">
                    <i class="fa fa-circle-o"></i> Dashboard
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-credit-card"></i>
            <span>Epay</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/index') ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/buy') ?>"><i class="fa fa-circle-o"></i> Buy Online PIN</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/report/index') ?>"><i class="fa fa-circle-o"></i> Report</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="">
            <i class="fa fa-signal"></i>
            <span>Mobile Pulsa</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('mobile/index') ?>">
                    <i class="fa fa-circle-o"></i> Dashboard
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cc-paypal"></i>
            <span>Voucher Manage</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('voucher/index') ?>">
                    <i class="fa fa-circle-o"></i> Dashboard
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="">
            <i class="fa fa-line-chart"></i>
            <span>Report</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('report/index') ?>">
                    <i class="fa fa-circle-o"></i> Dashboard
                </a>
            </li>
        </ul>
    </li>
</ul>
