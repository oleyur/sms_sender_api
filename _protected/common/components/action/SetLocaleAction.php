<?php
namespace common\components\action;

use yii\base\Action;
use yii\base\InvalidParamException;
use Yii;
use yii\web\Cookie;

/**
 * Class SetLocaleAction
 * @package common\components\action
 *
 * Example:
 *
 *   public function actions()
 *   {
 *       return [
 *           'set-locale'=>[
 *               'class'=>'common\components\actions\SetLocaleAction',
 *               'locales'=>[
 *                   'en-US', 'ru-RU', 'ua-UA'
 *               ],
 *           ]
 *       ];
 *   }
*/

class SetLocaleAction extends Action
{
    /**
     * @var array List of available locales
     */
    public $locales;


    /**
     * @param $locale
     * @return mixed|static
     */
    public function run($locale)
    {
        if(!is_array($this->locales) && !isset($this->locales[$locale])){
            throw new InvalidParamException('Unacceptable locale');
        }

        $defaultLang = array_keys($this->locales)[0];
        $redirectUrl = Yii::$app->request->referrer ?: Yii::$app->homeUrl;

        $host = $this->getServerNameWithoutLang();

        if($locale == $defaultLang){
            $redirectUrl = str_replace($_SERVER['HTTP_HOST'], $host, $redirectUrl);
        }else{
            $redirectUrl = str_replace($_SERVER['HTTP_HOST'], $locale.".".$host, $redirectUrl);
        }

        return Yii::$app->response->redirect($redirectUrl);
    }

    public function getServerNameWithoutLang(){

        $serverName = $_SERVER['HTTP_HOST'];
        foreach ((array)$this->locales as $lang => $info) {
            $serverName = str_replace($lang.".", "", $serverName);
        }
        return $serverName;
    }
}
