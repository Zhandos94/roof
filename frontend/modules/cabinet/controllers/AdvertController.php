<?php

namespace frontend\modules\cabinet\controllers;


use Yii;
use frontend\modules\cabinet\models\Advert;
use frontend\modules\cabinet\models\AdvertSearch;
use frontend\components\Common;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use Imagine\Image\Point;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * AdvertController implements the CRUD actions for Advert model.
 */
class AdvertController extends Controller
{

    public $layout = "inner";


    public function init()
    {
        Yii::$app->view->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyAlqjEAytdc4BAilabkGAPHNmYPfnBb6u4',['position' => \yii\web\View::POS_HEAD]);
    }

    /**
     * Lists all Advert models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new AdvertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advert model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Advert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advert();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['step2']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Advert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['step2']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Advert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStep2()
    {
        $id = Yii::$app->locator->cache->get('id');
        $model = Advert::findOne($id);
        $image = [];
        if ($general_image = $model->general_image) {
            $image[] = '/uploads/adverts/' . $model->idadvert . '/general/small_' . $general_image ;
//            $image[] = '<img src="/uploads/adverts/' . $model->idadvert . '/general/small_' . $general_image . '" width=250>';
//            $image[] = '<img src="/uploads/adverts/' . $model->idadvert . '/general/small_' . $general_image . '" width="50px" height="auto">';
//            $image[] = '<img src="/uploads/adverts/' . $model->idadvert . '/general/small_' . $general_image . '" class= "kv-preview-data file-preview-image">';
        }
//        var_dump($image);
//        die();

        if (Yii::$app->request->isPost) {

            $this->redirect(Url::to(['advert/']));
        }

        $path = Yii::getAlias("@frontend/web/uploads/adverts/" . $model->idadvert);
        $images_add = [];

        try {
            if (is_dir($path)) {
                $files = \yii\helpers\FileHelper::findFiles($path);

                foreach ($files as $file) {
                    if (strstr($file, "small_") && !strstr($file, "general")) {
                        $images_add[] = '/uploads/adverts/' . $model->idadvert . '/' . basename($file);
                    }
                }
            }
        } catch (\yii\base\Exception $e) {
        }

//        var_dump($images_add);
//        die();
        return $this->render("step2", [
            'model' => $model,
            'image' => $image,
            'images_add' => $images_add]);
    }

    public function actionFileUploadGeneral()
    {

        if (Yii::$app->request->isPost) {
            $success = false;
            $id = Yii::$app->request->post("advert_id");
            $path = Yii::getAlias("@frontend/web/uploads/adverts/" . $id . "/general");
            BaseFileHelper::createDirectory($path);
            $model = Advert::findOne($id);
            $model->scenario = 'step';

            $file = UploadedFile::getInstance($model, 'general_image');
            if (!empty($file)) {
                $name = 'general.' . $file->extension;
                $file->saveAs($path . DIRECTORY_SEPARATOR . $name);

                $image = $path . DIRECTORY_SEPARATOR . $name;
                $new_name = $path . DIRECTORY_SEPARATOR . "small_" . $name;

                $model->general_image = $name;
                $model->save();

                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];

                Image::frame($image, 0, '666', 0)
                    ->crop(new Point(0, 0), new Box($width, $height))
                    ->resize(new Box(1000, 644))
                    ->save($new_name, ['quality' => 100]);
                $success = true;
            }
            return json_encode($success);
        }
    }


    public function actionFileUploadImages()
    {
        if (Yii::$app->request->isPost) {
            $success = false;
            $id = Yii::$app->request->post("advert_id");
            $path = Yii::getAlias("@frontend/web/uploads/adverts/" . $id);
            BaseFileHelper::createDirectory($path);
            $file = UploadedFile::getInstanceByName('images');
            $model = Advert::findOne($id);
            $model->scenario = 'step';

//            $file[] = UploadedFile::getInstances($model, 'images');
//            var_dump(count($file));
//            die();

            if (!empty($file)) {

                $name = time() . '.' . $file->extension;
                $file->saveAs($path . DIRECTORY_SEPARATOR . $name);
                $image = $path . DIRECTORY_SEPARATOR . $name;
                $new_name = $path . DIRECTORY_SEPARATOR . "small_" . $name;

                $size = getimagesize($image);
                $width = $size[0];
                $height = $size[1];

                Image::frame($image, 0, '666', 0)
                    ->crop(new Point(0, 0), new Box($width, $height))
                    ->resize(new Box(1000, 644))
                    ->save($new_name, ['quality' => 100]);

                sleep(1);
                $success = true;
            }
            return json_encode($success);

        }
    }

    public function actionDeleteImage(){
        var_dump('true') ;
        die();
    }

    public function actionDeleteUploadImage()
    {
        $success = false;
        if (Yii::$app->request->isPost) {
            $link = Yii::$app->request->post('img');
            if (!empty($link)) {
                $path = Yii::getAlias("@frontend/web/");
                unlink($path . $link);
                unlink($path . Common::beforeSubstr('small_', $link) .
                    Common::afterSubstr('small_', $link));
                $success = true;
            }
        }
        return $success;
    }

    public function actionCacheTest()
    {
        $locator = \Yii::$app->locator;

        $locator->cache->set('test', 'base64_encode');

        print $locator->cache->get('test');

    }
}
