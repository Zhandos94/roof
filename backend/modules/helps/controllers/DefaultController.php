<?php

namespace backend\modules\helps\controllers;

use Yii;
use backend\modules\helps\models\HelpIntro;
use backend\modules\helps\models\HelpIntroSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\translate\models\SourceMessage;
use backend\modules\translate\models\Message;

/**
 * DefaultController implements the CRUD actions for HelpIntro model.
 */
class DefaultController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all HelpIntro models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new HelpIntroSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single HelpIntro model.
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
	 * Creates a new HelpIntro model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new HelpIntro();
		if ($model->load(Yii::$app->request->post())) {
			try {
				$sourceMessage = new SourceMessage;
				$sourceMessage->category = Yii::$app->controller->module->params['category'];
				$sourceMessage->message = $model->message;
				if ($sourceMessage->save() && $sourceMessage->saveTranslates()) {
					$message = Message::find()->where(['id' => $sourceMessage->id, 'language' => Yii::$app->language])->one();
					$message->translation = $model->body;
					$model->body = $sourceMessage->id;
					if ($model->save() && $message->save()) {
						return $this->redirect(['view', 'id' => $model->id]);
					}
				}
			} catch (Exception $e) {
			}

		}
		return $this->render('create', ['model' => $model]);
	}

	/**
	 * Updates an existing HelpIntro model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$sourceMessage = SourceMessage::findOne($model->body);
		$message = Message::find()->where(['id' => $model->body, 'language' => Yii::$app->language])->one();

		if ($model->load(Yii::$app->request->post()) && $sourceMessage->load(Yii::$app->request->post()) && $message->load(Yii::$app->request->post())) {
			if ($model->save() && $sourceMessage->save() && $message->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		} else {
			return $this->render('update', [
				'model' => $model,
				'sourceMessage' => $sourceMessage,
				'message' => $message,
			]);
		}
	}

	/**
	 * Deletes an existing HelpIntro model.
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
	 * Finds the HelpIntro model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return HelpIntro the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = HelpIntro::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
