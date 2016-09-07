<?php
use app\components\filters\AccessFilters;
?>
<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li class="treeview">
        <a href="<?= Yii::$app->urlManager->createUrl('dashboard') ?>">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <?php if (AccessFilters::getMenu('epay')) : ?>
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
    <?php endif; ?>
    <?php if (AccessFilters::getMenu('merchant-signup')) : ?>
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
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('speciality') ?>">
                    <i class="fa fa-circle-o"></i> Merchant Speciality Promo
                </a>
            </li>
        </ul>
    </li>
    <?php endif; ?>
    <?php if (AccessFilters::getMenu('voucher')) : ?>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-dollar"></i>
            <span>Redemption</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('voucher/default/reward') ?>">
                    <i class="fa fa-circle-o"></i> Reward
                </a>
            </li>
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('voucher/default/cash') ?>">
                    <i class="fa fa-circle-o"></i> Cash Voucher
                </a>
            </li>
        </ul>
    </li>
    <?php endif; ?>
    <?php if (AccessFilters::getMenu('account')) : ?>
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
    <?php endif; ?>
    <?php if (AccessFilters::getMenu('snapearn')) : ?>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-hand-pointer-o"></i>
            <span>Snap &amp; Earn</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?= Yii::$app->urlManager->createUrl('snapearn') ?>"><i class="fa fa-circle-o"></i> List</a></li>
            <?php if (AccessFilters::getMenu('logwork')) : ?>
            <li><a href="<?= Yii::$app->urlManager->createUrl('logwork') ?>"><i class="fa fa-circle-o"></i> Working Hours</a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php if (AccessFilters::getMenu('rbac')) : ?>
    <!-- RBAC -->
    <li class="treeview">
        <a href="#">
            <i class="fa fa-gears"></i>
            <span>User Management</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('snapearn/group/index') ?>">
                    <i class="fa fa-circle-o"></i> Snap &amp; Earn Group
                </a>
            </li>
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
    <?php endif; ?>
    
    <?php if (AccessFilters::getMenu('system')) : ?>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-university"></i>
            <span>System</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="<?= Yii::$app->urlManager->createUrl('system/merchant-hq') ?>">
                    <i class="fa fa-circle-o"></i> Merchant HQ
                </a>
            </li>
            
        </ul>
    </li>
    <?php endif; ?>
</ul>
