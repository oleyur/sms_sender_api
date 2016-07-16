<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$activation_url = Url::toRoute(["/user/sign-in/activate", "activation_key" => $user->activation_key], true);
?>

Welcome to activate your account in <?= Yii::$app->name?>
<br>
To activate your account click on the link below, please:"
<br>
<?= Html::a(Html::encode($activation_url), $activation_url) ?>
<br>
If you don't have account in this system, please ignore this message.
