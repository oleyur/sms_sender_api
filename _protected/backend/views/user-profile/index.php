<?php

use common\models\UserProfile;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t("backend", "User Profiles");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-index box box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'attribute'     => 'user_id',
                'headerOptions' => ['width' => 80],
            ],
            'firstname',
            'middlename',
            'lastname',
            [
                'attribute' => 'picture',
                'format'    => 'raw',
                'value'     => function ($data) {
                    $imgPath = $data->getFileUrl("picture", 'small');
                    if (!empty($imgPath)) {
                        $result = Html::img($imgPath);
                    } else {
                        $result = 'No';
                    }
                    return $result;
                },
                'filter'    => false,
            ],
            [
                'attribute' => 'gender',
                'value'     => function ($data) {
                    return UserProfile::getGenders($data->gender);
                },
                'filter'    => UserProfile::getGenders(),
            ],
            'locale',

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>
    
</div>
