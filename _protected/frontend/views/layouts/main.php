<?php
use common\components\Site;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php

            NavBar::begin([
                'brandLabel' => Yii::t('frontend', Yii::$app->name),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-default navbar-fixed-top',
                ],
            ]);

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => Yii::t('frontend', 'Home'), 'url' => ['/site/index']],
                    ['label' => Yii::t('frontend', 'About'), 'url' => ['/site/about']],
                    ['label' => Yii::t('frontend', 'Contact'), 'url' => ['/site/contact']],
                    ['label' => Yii::t('frontend', 'Signup'), 'url' => ['/user/sign-in/signup'], 'visible'=>Yii::$app->user->isGuest],
                    ['label' => Yii::t('frontend', 'Login'), 'url' => ['/user/sign-in/login'], 'visible'=>Yii::$app->user->isGuest],
                    [
                        'label' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->getPublicIdentity(),
                        'visible'=>!Yii::$app->user->isGuest,
                        'items'=>[
                            [
                                'label' => Yii::t('frontend', 'Account'),
                                'url' => ['/user/default/index']
                            ],
                            [
                                'label' => Yii::t('frontend', 'Profile'),
                                'url' => ['/user/default/profile']
                            ],
                            [
                                'label' => Yii::t('frontend', 'Backend'),
                                'url' => Yii::getAlias('@backendUrl')."/",
                                'visible'=>Yii::$app->user->can('manager')
                            ],
                            [
                                'label' => Yii::t('frontend', 'Logout'),
                                'url' => ['/user/sign-in/logout'],
                                'linkOptions' => ['data-method' => 'post']
                            ]
                        ]
                    ],
                    [
                        'label'=> Site::getLangs()[Site::getLang()]['name'],
                        'items'=> array_map(function($code){
                            return [
                                'label'  => Site::getLangs()[$code]['name'],
                                'url'    => ['/site/set-locale', 'locale' => $code],
                                'active' => Site::getLang() == $code
                            ];
                        }, array_keys(Site::getLangs()))
                    ]
                ],
            ]);

            NavBar::end();

        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="text-center">&copy; <?= Yii::t('frontend', Yii::$app->name) ?> <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
