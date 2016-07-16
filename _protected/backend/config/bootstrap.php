<?php

Yii::$container->set('yii\grid\GridView', [
    'layout' => "{items}\n{summary}\n<div class='text-center'>{pager}</div>",
]);
Yii::$container->set('yii\bootstrap\ActiveForm', [
    'layout' => 'horizontal',
    'options' => [
        'class' => 'box box-body box-form text-center',
    ],
    'fieldConfig' => [
        'horizontalCheckboxTemplate' => '<div class="form-group text-left"><div class="col-sm-offset-3 col-sm-6"><div class="checkbox"><label><input type="checkbox"> {labelTitle}</label></div></div></div>',
    ],
]);
Yii::$container->set('yii\grid\ActionColumn', [
    'contentOptions' => ['class' => 'td-action-column'],
]);