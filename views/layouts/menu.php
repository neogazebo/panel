<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <!-- <li class="treeview">
        <a href="#">
            <i class="fa fa-th-list"></i>
            <span>Partner</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('partner/index') ?>">
                    <i class="fa fa-circle-o"></i> Dashboard
                </a>
            </li>
        </ul>
    </li> -->
    <li class="treeview">
        <a href="#">
            <i class="fa fa-credit-card"></i>
            <span>Epay</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/index') ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/buy') ?>"><i class="fa fa-circle-o"></i> Buy Online PIN</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('epay/report') ?>"><i class="fa fa-circle-o"></i> Report</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th-list"></i>
            <span>Merchant Signup</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('merchant-signup') ?>">
                    <i class="fa fa-circle-o"></i> List
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>Member</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('account') ?>"><i class="fa fa-circle-o"></i> List</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-hand-pointer-o"></i>
            <span>Snap &amp; Earn</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('snapearn') ?>"><i class="fa fa-circle-o"></i> List</a></li>
        </ul>
    </li>
    <!-- <li class="treeview">
        <a href="">
            <i class="fa fa-signal"></i>
            <span>Mobile Pulsa</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('mobile/index') ?>"><i class="fa fa-circle-o"></i> Dashboard</a></li>
            <li><a href="<?= Yii::$app->urlManager->createUrl('mobile/log') ?>"><i class="fa fa-circle-o"></i> Transaction Log</a></li>
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
    </li> -->
    <!-- RBAC -->
    <li class="treeview">
        <a href="#">
            <i class="fa fa-gears"></i>
            <span>User Management</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('users/index') ?>">
                    <i class="fa fa-circle-o"></i> User
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('rbac/index') ?>">
                    <i class="fa fa-circle-o"></i> Role
                </a>
            </li>
        </ul>
    </li>
</ul>
