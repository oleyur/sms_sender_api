<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="background: #1a2226;">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->profile->getFileUrl("picture") ?:  \yii\helpers\Url::to('@backendUrl/images/avatar.png') ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::$app->formatter->asDatetime(time()) ?></a>
            </div>
        </div>

        <?php /*
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        */ ?>

        <?= backend\components\widgets\Menu::widget([
            'options'=>['class'=>'sidebar-menu'],
            'labelTemplate' => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
            'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
            'submenuTemplate'=>"\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
            'activateParents'=>true,
            'items'=>[
//                    [
//                        'label'=>Yii::t('backend', 'Timeline'),
//                        'icon'=>'<i class="fa fa-bar-chart-o"></i>',
//                        'url'=>['/system-event/timeline']
//                    ],

                [
                    'label'=>Yii::t('backend', 'Users'),
                    'icon'=>'<i class="fa fa-user"></i>',
                    'url'=>['/user/index'],
                    'visible'=>Yii::$app->user->can('admin')
                ],
                [
                    'label'=>Yii::t('backend', 'Users Profile'),
                    'icon'=>'<i class="fa fa-users"></i>',
                    'url'=>['/user-profile/index'],
                    'visible'=>Yii::$app->user->can('admin')
                ],

                [
                    'label'=>Yii::t('backend', 'System'),
                    'icon'=>'<i class="fa fa-dashboard"></i>',
//                    'options'=>['class'=>'treeview'],
                    'items'=>[
                        [
                            'label'=>Yii::t('backend', 'System Events'),
                            'url'=>['/system-event/index'],
                            'icon'=>'<i class="fa fa-circle-o"></i>',
//                            'badge'=>\common\models\SystemEvent::find()->today()->count(),
//                            'badgeBgClass'=>'bg-green',
                        ],
                        [
                            'label'=>Yii::t('backend', 'Logs'),
                            'url'=>['/log/index'],
                            'icon'=>'<i class="fa fa-circle-o"></i>',
//                            'badge'=>\backend\models\SystemLog::find()->count(),
//                            'badgeBgClass'=>'bg-red',
                        ],
                    ]
                ]
            ]
        ]) ?>

    </section>

</aside>
