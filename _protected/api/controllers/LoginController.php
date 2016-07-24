<?php
namespace api\controllers;

use api\models\LoginUser;
use Yii;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class LoginController extends ActiveController
{

    public $modelClass = 'api\models\LoginUser';

    public function actions()
    {
        return null;
    }


    public function actionLogin()
    {
        /* @var $model LoginUser */
        $model = new $this->modelClass();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        /* @var $user \common\models\User */
        $user = $model->login();
        $response = Yii::$app->getResponse();
        if ($user) {
            $response->setStatusCode(203);
            $response->data = [
                'id'           => $user->id,
                'access_token' => $user->access_token,
                'email'        => $user->email,
                'firstname'    => $user->profile->firstname,
                'lastname'     => $user->profile->lastname,
            ];
        }elseif (!$model->validate() && $model->hasErrors()) {
            $response->setStatusCode(401);
            $response->data = $model->getErrors();
        } else {
            $response->setStatusCode(401);
            $response->data = $response->data = 'User not login for unknown reason';
        }
        return $response;
    }

}