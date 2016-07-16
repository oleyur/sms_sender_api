<?php
namespace common\components\behaviors;

use common\components\Site;
use yii\base\Behavior;
use Yii;
use yii\web\Application;

/**
 * Class LocaleBehavior
 * @package common\components\behaviors
 */

class LocaleBehavior extends Behavior{

    /**
     * @return array
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST=>'beforeRequest'
        ];
    }

    /**
     * Resolve application language by checking subdomain
     */
    public function beforeRequest(){
        $lang = $this->getLangBySubdomain();
        Yii::$app->language = Site::getLangs()[$lang]['value'];
    }

    public function getLangBySubdomain(){

        $langs = Site::getLangs();
        $result = array_keys($langs)[0];

        foreach ((array)$langs as $lang => $info) {
            if(substr($_SERVER['HTTP_HOST'], 0, strlen($lang)+1) == $lang."."){
                $result = $lang;
                break;
            }
        }

        return $result;
    }

}