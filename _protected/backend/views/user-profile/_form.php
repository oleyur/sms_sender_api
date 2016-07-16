<?php

use common\components\Site;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-profile-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'box box-body box-form text-center']]); ?>

    <?php $image_path = $model->getFileUrl("picture", "small") ?>

    <?php echo !empty($image_path)?Html::img($image_path):""; ?>

    <?= $form->field($model, 'picture')->fileInput(['accept' => 'image/*']) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'middlename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'locale')->dropDownlist(ArrayHelper::map(Site::getLangs(), 'code', 'name')) ?>

    <?= $form->field($model, 'gender')->dropDownlist(UserProfile::getGenders()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
