<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('frontend', 'Congratulations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup-success text-center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t("frontend", "We sent you e-mail to the registration confirmation. Please, activate your account.")?></p>

</div>