<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 14.02.2017
 * Time: 16:58
 */

namespace frontend\widgets;


use common\models\LoginForm;
use yii\bootstrap\Widget;

class LoginWidget extends Widget
{
    public function run()
    {
        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            $controller = \Yii::$app->controller;
//            $controller->redirect($controller->goBack());
            $controller->goBack();
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

}