<?php

namespace common\assets;

use yii\web\AssetBundle;

class MaterialDesignAsset extends AssetBundle
{

    public $sourcePath = '@bower/bootstrap-material-design/dist';
    public $baseUrl = '@web';

    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $css = [
        'css/material.min.css' => 'css/bootstrap-material-design.css',
        'css/ripples.min.css',
    ];
    public $js = [
        'js/material.min.js',
        'js/ripples.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init(){
        parent::init();
    }
}