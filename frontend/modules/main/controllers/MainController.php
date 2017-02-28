<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 22.12.2016
 * Time: 22:00
 */

namespace frontend\modules\main\controllers;

use common\models\LoginForm;
use frontend\filters\FilterAdvert;
use frontend\models\ContactForm;
use frontend\models\SignupForm;
use frontend\modules\cabinet\models\Advert;
use kartik\form\ActiveForm;
use Yii;
use yii\base\DynamicModel;
use yii\data\Pagination;
use yii\web\Response;
use frontend\components\Common;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Marker;

class MainController extends \yii\web\Controller
{
    public $layout = "inner";

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'test' => [
                'class' => 'frontend\actions\TestAction',
                'viewName' => 'test'
            ],
            'page' => [
                'class' => 'yii\web\ViewAction',
                'layout' => 'inner',
            ]

        ];
    }

    public function behaviors()
    {
        return [
            [
                'only' => ['view-advert'],
                'class' => FilterAdvert::className(),
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            $this->goBack();
        }

        return $this->render("login", ['model' => $model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionRegister()
    {

        $model = new SignupForm();

        if (\Yii::$app->request->isAjax && \Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {

            \Yii::$app->session->setFlash('success', 'Register Success');
            return $this->redirect("../default/index");

        }

        return $this->render("register", ['model' => $model]);
    }

    /**
     * @return string
     */
    public function actionContact()
    {

        ///Google Map

        $coord = new LatLng(['lat' => '43.2220146', 'lng' => '76.8512485']);

        $map = new Map([
            'center' => $coord,
            'zoom' => 13,
        ]);

        $marker = new Marker([
            'position' => $coord,
            'title' => 'Almaty',
        ]);

        $map->addOverlay($marker);
        ///End Google Map

        $model = new ContactForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            Common::Mail($model->subject, $model->email, $model->body, $model->name);
            $this->refresh();
        }

        return $this->render("contact", ['model' => $model, 'map' => $map]);
    }

    public function actionFind($propert = '', $price = '', $apartment = '')
    {

        $this->layout = 'sell';

        $query = Advert::find();
        $query->filterWhere(['like', 'address', $propert])
            ->orFilterWhere(['like', 'description', $propert])
            ->andFilterWhere(['type' => $apartment]);

        if ($price) {
            $prices = explode("-", $price);
            if (isset($prices[0]) && isset($prices[1])) {
                $query->andWhere(['between', 'price', $prices[0], $prices[1]]);
            } else {
                $query->andWhere(['>=', 'price', $prices[0]]);
            }
        }

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(3);

        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        $request = \Yii::$app->request;

        return $this->render("find", ['model' => $model, 'pages' => $pages, 'request' => $request]);

    }

    public function actionViewAdvert($id)
    {
        $model = Advert::findOne($id);
        $images = Common::getSliderImage($model, false);

        $data = ['name', 'email', 'text'];
        $model_feeback = new DynamicModel($data);
        $model_feeback->addRule('name', 'required');
        $model_feeback->addRule('email', 'required');
        $model_feeback->addRule('text', 'required');
        $model_feeback->addRule('email', 'email');
        $curent_user = ['name' => '', 'email' => ''];

        if (!\Yii::$app->user->isGuest) {
            $curent_user['name'] = \Yii::$app->user->identity->username;
            $curent_user['email'] = \Yii::$app->user->identity->email;
        }

        if (\Yii::$app->request->isPost) {
            if ($model_feeback->load(\Yii::$app->request->post()) && $model_feeback->validate()) {
                Common::Mail('Subject Advert', $model_feeback->email, $model_feeback->text, $model_feeback->name);
                Yii::$app->session->setFlash('success', 'Message has sended successfully');
            } else {
                \Yii::error($model_feeback->getErrors(), 'advert');
            }
        }
        ///Google Map
        $coords = str_replace(['(', ')'], '', $model->location);
        $coords = explode(',', $coords);

        $coord = new LatLng(['lat' => $coords[0], 'lng' => $coords[1]]);

        $map = new Map([
            'center' => $coord,
            'zoom' => 13,
        ]);

        $marker = new Marker([
            'position' => $coord,
            'title' => Common::getTitle($model),
        ]);

        $map->addOverlay($marker);
        ///End Google Map

        return $this->render('view-advert', [
            'model' => $model,
            'images' => $images,
            'model_feeback' => $model_feeback,
            'curent_user' => $curent_user,
            'map' => $map,

        ]);
    }

}
