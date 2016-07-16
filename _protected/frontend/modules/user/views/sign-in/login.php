<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\modules\user\models\LoginForm */

$this->title = Yii::t('frontend', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-offset-2 col-lg-8">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'layout' => 'horizontal']); ?>
            <?= $form->field($model, 'identity') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('frontend', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <br>

            <h3 class="text-center"><?php echo Yii::t('frontend', 'or log in with') ?>:</h3>
            <div class="form-group">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/user/sign-in/oauth'],
                    'popupMode'   => false,
                ]) ?>
            </div>

            <br>


            <div style="color:#999;" class="text-center">
                <?php echo Yii::t('frontend', 'If you forgot your password you can reset it') ?>
                <a href="<?= yii\helpers\Url::to(['sign-in/request-password-reset']) ?>"><?php echo Yii::t("frontend", "here") ?></a>
            </div>
            <div class="form-group text-center">
                <?php echo Html::a(Yii::t('frontend', 'Need an account? Sign up.'), ['signup']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>