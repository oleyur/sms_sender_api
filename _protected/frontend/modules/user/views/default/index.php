<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('frontend', 'Account')
?>

<div class="user-profile-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-offset-2 col-lg-8">

            <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'password_confirm')->passwordInput() ?>

            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('frontend', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
