<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\modules\user\models\PasswordResetRequestForm */

$this->title = Yii::t('frontend', 'Password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-offset-2 col-lg-8">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'layout' => 'horizontal']); ?>

            <?= $form->field($model, 'email') ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
