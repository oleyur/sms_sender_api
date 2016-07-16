<?php
namespace api\controllers;

use yii\rest\ActiveController;

class ImageController extends ActiveController{

    public $modelClass = 'common\models\Images';





    /**
     * @var string the scenario used for creating a model.
     */
    //public $createScenario = "insert";
    /**
     * @var string the scenario used for updating a model.
     */
    //public $updateScenario = "update";



/*
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                // if in a module, use the following IDs for user actions
                // 'only' => ['user/view', 'user/index']
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }
*/
}


/*

//add image
curl title=mytitle -F Images[image]=@/var/www/flower.jpg http://basic_improved.dev/api/image

 */


