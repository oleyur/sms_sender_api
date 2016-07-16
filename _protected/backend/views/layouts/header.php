<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"> <i class="fa fa-home" aria-hidden="true"></i> </span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

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
                        <?php
                        $systemLogCount = \backend\models\SystemLog::find()->count();
                        if(!empty($systemLogCount)){
                            ?>
                            <span class="badge bg-red">
                                <?php echo $systemLogCount; ?>
                                </span>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= Yii::t('backend', 'You have {num} log items', ['num'=>\backend\models\SystemLog::find()->count()]) ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php  foreach(\backend\models\SystemLog::find()->orderBy(['log_time'=>SORT_DESC])->limit(5)->all() as $logEntry): ?>
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
                        <li class="user-header">
                            <img src="<?= Yii::$app->user->identity->profile->getFileUrl("picture") ?: \yii\helpers\Url::to('@backendUrl/images/avatar.png') ?>" class="img-circle" alt="User Image" />
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
                            <div class="pull-left" style="margin-left: 5px;">
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
