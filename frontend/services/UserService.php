<?php
/**
 * Created by BADI.
 * DateTime: 03.11.2016 18:31
 */

namespace frontend\services;


use Yii;

class UserService
{
    public static function getNickname()
    {
        return Yii::$app->user->identity->username;
    }

    public static function getUserId()
    {
        return Yii::$app->user->identity->id;
    }
}
