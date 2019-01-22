<?php
/**
 * Email: srdjandrakul@gmail.com
 * Date: 1/17/2019
 * Time: 11:40 PM
 */

namespace srdjan\floatingmenu;

use yii\web\AssetBundle;

class FloatingMenuAsset extends AssetBundle
{
    public $sourcePath = '@vendor/srdjan92/yii2-floating-menu/src/assets';

    public $js = [
        'js/floating-menu.js'
    ];

    public $css = [
        'css/floating-menu.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}