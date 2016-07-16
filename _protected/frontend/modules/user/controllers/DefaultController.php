<?php

namespace frontend\modules\user\controllers;

use common\models\User;
use frontend\modules\user\models\AccountForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = new AccountForm();
        $model->username = $user->username;
        if($model->load($_POST) && $model->validate()){
            $user->username = $model->username;
            $user->setPassword($model->password);
            $user->save();
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'Your profile has been successfully saved'));

            return $this->refresh();
        }
        return $this->render('index', ['model'=>$model]);
    }

    public function actionProfile()
    {

        /** @var User $user */
        $user = Yii::$app->user->identity;

        $model = $user->profile;
        if($model->load($_POST) && $model->save()){
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'Your profile has been successfully saved'));
            return $this->refresh();
        }
        return $this->render('profile', ['model'=>$model]);
    }
}
