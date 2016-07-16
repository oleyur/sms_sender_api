<?php

use common\components\Site;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('frontend', 'Profile');


$img = $model->getFileUrl("picture", "small");
if (!$img) {
    $img = '/images/avatar.png';
}


?>

<div class="user-profile-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-offset-2 col-lg-8">
            <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="profile-img-box text-center">
                <img src="<?= $img ?>" alt="avatar">
            </div>
            <br>

            <?= $form->field($model, 'picture')->fileInput(['accept' => 'image/*']) ?>

            <?= $form->field($model, 'firstname')->textInput(['maxlength' => 255]) ?>

            <?php /*echo $form->field($model, 'middlename')->textInput(['maxlength' => 255]) */?>

            <?= $form->field($model, 'lastname')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'locale')->dropDownlist(ArrayHelper::map(Site::getLangs(), 'code', 'name')) ?>

            <?= $form->field($model, 'gender')->dropDownlist(UserProfile::getGenders()) ?>

            <div class="form-group text-center">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create') : Yii::t('frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
