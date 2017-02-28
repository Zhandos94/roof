<?php

namespace frontend\modules\cabinet\controllers;

use frontend\models\ChangePassword;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Default controller for the `cabinet` module
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

        return $this->render('index');
    }


    public function actionChangePassword(){

        $this->layout = "inner";
        $model = new ChangePassword();

        if($model->load(\Yii::$app->request->post()) && $model->changepassword()){
            return $this->refresh();
        }

        return $this->render('change-password', ['model' => $model]);
    }
}
