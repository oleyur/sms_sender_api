<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/3/14
 * Time: 3:14 PM
 */

namespace backend\assets;


use Yii;
use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $basePath = '/';
    public $baseUrl = '@backendUrl';
    public $css = [
        'css/style.css',
    ];


    public $js = [
        'js/js.cookie.js',
        'js/app.js',
    ];


    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\Html5shiv',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];


    /**
     *
     */
    public function init()
    {

        if (!empty($this->css)) {
            foreach ((array)$this->css as $key => $item) {
                $path = Yii::getAlias('@webroot') . '/' . $item;
                $this->css[$key] = $item . '?' . md5_file($path);
            }
        }
        if (!empty($this->js)) {
            foreach ((array)$this->js as $key => $item) {
                $path = Yii::getAlias('@webroot') . '/' . $item;
                $this->js[$key] = $item . '?' . md5_file($path);
            }
        }

        parent::init();
    }


}