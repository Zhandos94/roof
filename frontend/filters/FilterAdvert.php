<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 14.02.2017
 * Time: 21:14
 */
namespace frontend\filters;

use frontend\modules\cabinet\models\Advert;
use yii\base\ActionFilter;
use yii\web\HttpException;

class FilterAdvert extends ActionFilter
{
    public function beforeAction($action)
    {
        $id = \Yii::$app->request->get('id');

        $model = Advert::findOne($id);
        if ($model == null) {
            throw new HttpException(404, 'Unknown advert');
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }


    public function afterAction($action,$result){
        return parent::afterAction($action,$result);
    }

}