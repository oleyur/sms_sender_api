<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
            'modelClass' => 'User',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'attribute'     => 'id',
                'headerOptions' => ['width' => 50],
            ],
            'username',
            'email:email',
            [
                'attribute' => 'role',
                'value'     => function ($data) {
                    return User::getRoles($data->role);
                },
                'filter'    => User::getRoles(),
            ],
            [
                'attribute' => 'status',
                'value'     => function ($data) {
                    return User::getStatuses($data->status);
                },
                'filter'    => User::getStatuses(),
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{profile} {update} {delete}',
                'buttons'  => [
                    'profile' => function ($url, $model) {
                        $url = Url::toRoute(['user-profile/index', 'UserProfileSearch[user_id]' => $model->id]);
                        return Html::a('<span class="fa fa-users"></span>', $url, ['title' => Yii::t('yii', 'User profile')]);
                    },
                ],
            ],

        ],
    ]); ?>

</div>
