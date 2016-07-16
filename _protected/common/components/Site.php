<?php

namespace common\components;

use Yii;
use yii\base\Component;
class Site extends Component{

    /*
     * List of available locales
     * @return array
     */
    public static function getLangs(){
        $result = [];
        foreach ((array)Yii::$app->params['availableLocales'] as $key => $val) {
            $result[$key] = $val;
        }

        return $result;
    }

    /*
     * Current locale
     * @return string
     */
    public static function getLang(){
        list($lang) = explode("-", Yii::$app->language);
        return $lang;
    }

    /*
     * List of available currency
     * @return array
     */
    public static function getCurrencies(){
        return Yii::$app->params['availableCurrency'];
    }

    /*
     * Current currency
     * @return string
     */
    public static function getCurrency(){
        if(Yii::$app->getRequest()->getCookies()->has("_currency")){
            $userCurrency = Yii::$app->getRequest()->getCookies()->getValue("_currency");
        } else {
            $userCurrency = array_keys(self::getCurrencies());
            $userCurrency = $userCurrency[0];
        }

        return $userCurrency;
    }


    /**
     * Modify lang code for flags widget
     * @param $lang
     * @return string
     */
    public static function getFlagCode($lang){

        $code = strtoupper($lang);
        if ($code == 'US' || $code == 'EN') {
            $code = 'GB';
        }

        return $code;
    }

}