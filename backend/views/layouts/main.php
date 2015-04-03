﻿<?php
/**
 * @var $this yii\web\View
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginContent('@backend/views/layouts/_base.php'); ?>
<!-- header logo: style can be found in header.less -->
<header class="header">
    <a href="<?= Yii::getAlias('@frontendUrl') ?>" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <?= Yii::$app->name ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?= Yii::t('backend', 'Toggle navigation') ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li id="notifications-dropdown" class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span class="badge bg-green">
                            <?= \common\models\SystemEvent::find()->today()->count() ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">
                            <?= Yii::t('backend', 'You have {num} events', ['num'=>\common\models\SystemEvent::find()->today()->count()]) ?>
                        </li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php foreach(\common\models\SystemEvent::find()->today()->orderBy(['created_at'=>SORT_DESC])->limit(10)->all() as $eventRecord): ?>
                                    <li>
                                        <a href="<?= Yii::$app->urlManager->createUrl(['/system-event/view', 'id'=>$eventRecord->id]) ?>">
                                            <i class="fa fa-bell"></i>
                                            <?= $eventRecord->getName() ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <?= Html::a(Yii::t('backend', 'View all'), ['/system-event/index']) ?>
                            <?= Html::a(Yii::t('backend', 'Timeline'), ['/system-event/timeline']) ?>
                        </li>
                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <li id="log-dropdown" class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-warning"></i>
                        <span class="badge bg-red">
                            <?= \backend\models\SystemLog::find()->count() ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= Yii::t('backend', 'You have {num} log items', ['num'=>\backend\models\SystemLog::find()->count()]) ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php foreach(\backend\models\SystemLog::find()->orderBy(['log_time'=>SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                    <li>
                                        <a href="<?= Yii::$app->urlManager->createUrl(['/log/view', 'id'=>$logEntry->id]) ?>">
                                            <i class="fa fa-warning <?= $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'bg-red' : 'bg-yellow' ?>"></i>
                                            <?= $logEntry->category ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <?= Html::a(Yii::t('backend', 'View all'), ['/log/index']) ?>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?= Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="<?= Yii::$app->user->identity->profile->picture ?: '/img/anonymous.jpg' ?>" class="img-circle" alt="User Image" />
                            <p>
                                <?php Yii::$app->user->identity->username ?>
                                <small>
                                    <?= Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at) ?>
                                </small>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class'=>'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-left">
                                <?= Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class'=>'btn btn-default btn-flat']) ?>
                            </div>
       
                            <div class="pull-right">
                                <?= Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class'=>'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= Yii::$app->user->identity->profile->picture ?: '/img/anonymous.jpg' ?>" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p><?= Yii::t('backend', 'Hello, {username}', ['username'=>Yii::$app->user->identity->username]) ?></p>
                    <a href="<?php echo \yii\helpers\Url::to(['/sign-in/profile']) ?>">
                        <i class="fa fa-circle text-success"></i>
                        <?= Yii::$app->formatter->asDatetime(time()) ?>
                    </a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?= backend\components\widgets\Menu::widget([
                'options'=>['class'=>'sidebar-menu'],
                'labelTemplate' => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                'submenuTemplate'=>"\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                'activateParents'=>true,
                'items'=>[
/*                
                    [
                        'label'=>Yii::t('backend', 'Timeline'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/system-event/timeline']
                    ],
                    [
                        'label'=>Yii::t('backend', 'Content'),
                        'icon'=>'<i class="fa fa-edit"></i>',
                        'options'=>['class'=>'treeview'],
                        'items'=>[
                            ['label'=>Yii::t('backend', 'Static pages'), 'url'=>['/page/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Articles'), 'url'=>['/article/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Article Categories'), 'url'=>['/article-category/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Text Widgets'), 'url'=>['/widget-text/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Menu Widgets'), 'url'=>['/widget-menu/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Carousel Widgets'), 'url'=>['/widget-carousel/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
*/
                    [
                        'label'=>Yii::t('backend', 'Users'),
                        'icon'=>'<i class="fa fa-users"></i>',
                        'url'=>['/user/index'],
                        'visible'=>Yii::$app->user->can('administrator')
                    ],
                    


                    //our menu begin ---------------------------------------------------
                    [
                        'label'=>Yii::t('backend', 'School'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/school/index']
                    ],

/*
                    [
                        'label'=>Yii::t('backend', 'SchoolBranch'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/schoolbranch/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Room'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/room/index']
                    ],
*/
                    [
                        'label'=>Yii::t('backend', 'Teacher'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/teacher/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Course'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/course/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Student'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/student/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Group'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/class/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Parent'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/parent/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Reserve'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/reserve/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'Recommend'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/recommend/index']
                    ],


/*
                    [
                        'label'=>Yii::t('backend', 'CourseUnit'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/courseunit/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'CourseSchedule'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/courseschedule/index']
                    ],

                    [
                        'label'=>Yii::t('backend', 'CourseScheduleSignon'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/courseschedulesignon/index']
                    ],  

                    [
                        'label'=>Yii::t('backend', 'StudentCourse'),
                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                        'url'=>['/studentcourse/index']
                    ],
*/
                    [
                        'label'=>Yii::t('backend', 'Photo'),
                        'icon'=>'<i class="fa fa-file-image-o"></i>',
                        'url'=>['/photo/index']
                    ],


                    //设置菜单
                    [
                        'label'=>Yii::t('backend', 'Settings'),
                        'icon'=>'<i class="fa fa-cogs"></i>',
                        'options'=>['class'=>'treeview'],
                        'items'=>[

                            [
                                'label'=>Yii::t('backend', '自定义设置'),
                                'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                'url'=>['/keyword/index']
                            ],

                            [
                                'label'=>Yii::t('backend', 'WxAction'),
                                'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                'url'=>['/wxaction/index']
                            ],

                            [
                                'label'=>Yii::t('backend', 'WxMenu'),
                                'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                'url'=>['/wxmenu/index']
                            ],

                        ]
                    ],
                    //our menu end ---------------------------------------------------

                    [
                        'label'=>Yii::t('backend', 'System'),
                        'icon'=>'<i class="fa fa-cogs"></i>',
                        'options'=>['class'=>'treeview'],
                        'items'=>[
                            [
                                'label'=>Yii::t('backend', 'i18n'),
                                'icon'=>'<i class="fa fa-flag"></i>',
                                'options'=>['class'=>'treeview'],
                                'items'=>[
                                    ['label'=>Yii::t('backend', 'i18n Source Message'), 'url'=>['/i18n/i18n-source-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                    ['label'=>Yii::t('backend', 'i18n Message'), 'url'=>['/i18n/i18n-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ]
                            ],
                            ['label'=>Yii::t('backend', 'Key-Value Storage'), 'url'=>['/key-storage/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'File Storage Items'), 'url'=>['/file-storage/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'File Manager'), 'url'=>['/file-manager/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            [
                                'label'=>Yii::t('backend', 'System Events'),
                                'url'=>['/system-event/index'],
                                'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                'badge'=>\common\models\SystemEvent::find()->today()->count(),
                                'badgeBgClass'=>'bg-green',
                            ],
                            [
                                'label'=>Yii::t('backend', 'System Information'),
                                'url'=>['/system-information/index'],
                                'icon'=>'<i class="fa fa-angle-double-right"></i>'
                            ],
                            [
                                'label'=>Yii::t('backend', 'Logs'),
                                'url'=>['/log/index'],
                                'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                'badge'=>\backend\models\SystemLog::find()->count(),
                                'badgeBgClass'=>'bg-red',
                            ],


                        ]
                    ]
              



                ]
            ]) ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->title ?>
                <?php if(isset($this->params['subtitle'])): ?>
                    <small><?= $this->params['subtitle'] ?></small>
                <?php endif; ?>
            </h1>

            <?= Breadcrumbs::widget([
                'tag'=>'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if(Yii::$app->session->hasFlash('alert')):?>
                <?= \yii\bootstrap\Alert::widget([
                    'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                    'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                ])?>
            <?php endif; ?>
            <?= $content ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<?php $this->endContent(); ?>
