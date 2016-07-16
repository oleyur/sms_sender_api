<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$activation_url = Url::toRoute(["/user/sign-in/activate", "activation_key" => $user->activation_key], true);
?>

Вы создали учетную запись в <?= Yii::$app->name?>
<br>
Для активации Вашей учетной записи нажмите пожалуйста на ссылку ниже:
<br>
<?= Html::a(Html::encode($activation_url), $activation_url) ?>
<br>
Если вы не создавали учетную запись, проигнорируйте данное сообщение.