<?php

namespace frontend\modules\main\controllers;

use frontend\modules\cabinet\models\Advert;
use yii\web\Controller;


/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = "default";
        $query = Advert::find()->orderBy(['idadvert' => SORT_DESC]);

        $slider_data = $query->limit(5)->asArray()->all();
        $slider_data_count = $query->count();
        $property = $query->asArray()->limit(15)->all();

        $recommend_query = $query->where('recommend = 1')->limit(5);
        $recommend = $recommend_query->all();
        $recommend_count = $recommend_query->count();

        return $this->render('index', [
            'slider_data' => $slider_data,
            'slider_data_count' => $slider_data_count,
            'property' => $property,
            'recommend' => $recommend,
            'recommend_count' => $recommend_count,
        ]);
    }
}
