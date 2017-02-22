<?php

/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 14.02.2017
 * Time: 20:59
 */
namespace frontend\widgets;

use common\models\Subscribe;
use yii\bootstrap\Widget;

class SubscribeWidget extends  Widget{

    public function run(){
        $model = new Subscribe();

        if($model->load(\Yii::$app->request->post()) && $model->save()){

            \Yii::$app->session->setFlash('message','Success subscribe');
            \Yii::$app->controller->redirect("/");
        }

        return $this->render("subscribe", ['model' => $model]);
    }
}