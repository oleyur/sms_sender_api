<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */

$this->title = Yii::t("backend", "Update User Profile").': ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t("common", 'User Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-profile-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
