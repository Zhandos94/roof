<?php

/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 09.02.2017
 * Time: 15:32
 */
namespace common\cache;

use yii\caching\FileCache;

class Base64Cache extends FileCache
{
    public $cacheFileSuffix = '.base64';

    protected function getValue($key)
    {
        $value = base64_decode(parent::getValue($key));

        return $value;
    }

    protected function setValue($key, $value, $duration)
    {
        $value = base64_encode($value);
        parent::setValue($key, $value, $duration);
    }
}