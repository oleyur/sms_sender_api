<?php
namespace api\controllers;

use api\models\SignupUser;
use Yii;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class SignUpController extends ActiveController
{

    public $modelClass = 'api\models\SignupUser';

    public function actions()
    {
        return null;
    }


    public function actionCreate()
    {

        /* @var $model SignupUser */
        $model = new $this->modelClass();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $response = Yii::$app->getResponse();

        $result = $model->signup();
        if ($result) {
            $response->setStatusCode(201);
            $response->data = [
                'id'           => $result['user']->id,
                'access_token' => $result['user']->access_token,
                'email'        => $result['user']->email,
                'firstname'    => $result['user_profile']->firstname,
                'lastname'     => $result['user_profile']->lastname,
            ];

            return $response;

        } else {
            $response->setStatusCode(401);
            if (!$model->validate() && $model->hasErrors()) {
                $response->data = $model->getErrors();
            } else {
                $response->data = ['User not created for unknown reason'];
            }
            return $response;
        }

    }


}