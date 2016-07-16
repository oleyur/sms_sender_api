<?php
/**
 * -----------------------------------------------------------------------------
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * -----------------------------------------------------------------------------
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use Yii;

// set @themes alias so we do not have to update baseUrl every time we change themes
Yii::setAlias('@themes', Yii::$app->view->theme->baseUrl);

/**
 * -----------------------------------------------------------------------------
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 * -----------------------------------------------------------------------------
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@themes';

    public $css = [
        'css/bootstrap-modify.css',
        'css/site.css',
    ];
    public $js = [
        'js/site.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'frontend\assets\FontAwesome',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];


    /**
     *
     */
    public function init()
    {
        if (!empty($this->css)) {
            foreach ((array)$this->css as $key => $item) {
                $path = Yii::getAlias($this->basePath) . Yii::$app->view->theme->baseUrl . '/' . $item;
                $this->css[$key] = $item . '?' . md5_file($path);
            }
        }
        if (!empty($this->js)) {
            foreach ((array)$this->js as $key => $item) {
                $path = Yii::getAlias($this->basePath) . Yii::$app->view->theme->baseUrl . '/' . $item;
                $this->js[$key] = $item . '?' . md5_file($path);
            }
        }

        parent::init();
    }
}

