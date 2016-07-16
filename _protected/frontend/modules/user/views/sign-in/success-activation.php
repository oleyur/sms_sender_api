<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('frontend', 'Congratulations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-activation-success text-center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t("frontend","Your account has been successfully activated.")?></p>
    <a href="<?= Url::toRoute(["/user/sign-in/login"]) ?>"><?=Yii::t("frontend","Login")?></a>

</div>