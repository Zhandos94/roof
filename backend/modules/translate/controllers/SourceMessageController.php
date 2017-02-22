<?php

namespace backend\modules\translate\controllers;

//use backend\modules\translate\models\SendTranslation;
use backend\modules\translate\models\SourceMessage;
use backend\modules\translate\models\SourceMessageSearch;
use backend\modules\translate\models\ZetTranslateData;
use common\modules\translate\services\WriteToJsService;
use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * SourceMessageController implements the CRUD actions for SourceMessage model.
 */
class SourceMessageController extends Controller
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
//                    'delete-multiple' => ['POST']
				],
			],
		];
	}

	/**
	 * Lists all SourceMessage models.
	 * @param $not_translate_lang string
	 * @param $cyrillic boolean
	 * @return mixed
	 */
	public function actionIndex($not_translate_lang = null, $cyrillic = false)
	{
		$searchModel = new SourceMessageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $cyrillic);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'not_translate_lang' => $not_translate_lang,
			'cyrillic' => $cyrillic
		]);
	}

	/**
	 * Creates a new SourceMessage model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new SourceMessage();

		if ($model->load(Yii::$app->request->post()) && $model->save() && $model->saveTranslates()) {
			$jsService = new WriteToJsService();
			$jsService->execute();
//        if ($model->load(Yii::$app->request->post()) && $model->save() && $model->createMessages()) {
			return $this->redirect(['index', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing SourceMessage model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$model->existing_translates();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if ($model->saveTranslates()) {
				$jsService = new WriteToJsService();
				$jsService->execute();
				return $this->redirect('index');
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('app', 'language translates not saved'));
				return $this->render('update', [
					'model' => $model,
				]);
			}
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing SourceMessage model.
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
	 * Finds the SourceMessage model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SourceMessage the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = SourceMessage::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionLoadExcel($replace = false)
	{
		$model = new SourceMessage();
		$model->excelFile = UploadedFile::getInstance($model, 'excelFile');
		$transaction = Yii::$app->db->beginTransaction();
		try {

			$status_ok = false;
			if (SourceMessage::createArchiveModels()) {
				Yii::info("\n" . '>>>>>>>>>>>>>>>loading from document ' . ($replace ? 'with REPLACE' : '') . ' is started', 'translate_log');
				if ($model->saveFromExcel($replace, $model->excelFile->tempName)) {
					Yii::info("\n" . '<<<<<<<<<<<<<<<<<loading has come to the end successfully' . "\n"
						. 'new - ' . $model->new_msg . ', fill - ' . $model->empty_msg . ', replace - ' . $model->rpl_msg, 'translate_log');
					$status_ok = true;
				} else {
					Yii::info(['<<<<<<<<<<<<<<<loading ended with error'], 'translate_log');
					Yii::$app->getSession()->setFlash('error', Yii::t('app', 'language translates not saved'));
				}
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Can not create archives'));
			}
			if ($status_ok) {
				$transaction->commit();
			}
		} catch (Exception $e) {
			$transaction->rollBack();
		}

		$jsService = new WriteToJsService();
		$jsService->execute();
		return $this->redirect(['index']);
	}

	public function actionDeleteMultiple()
	{
		$keys = Yii::$app->request->post('keys');

		foreach ($keys as $key) {
			$this->findModel($key)->delete();
		}
		return $this->redirect(['index']);
	}

	public function actionStat()
	{
		return $this->render('stat');
	}

	public function actionRevert()
	{
		if (ZetTranslateData::getLastVersion() === null) {
			Yii::$app->session->setFlash('error', Yii::t('app', 'Not have backup copies'));
			return $this->redirect('index');
		}
		$transaction = Yii::$app->db->beginTransaction();
		try {
			$success = false;
			SourceMessage::deleteAll();
			Yii::info("\n" . '<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< REVERT START >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', 'translate_log');
			if (SourceMessage::revertModels()) {
				$success = true;
			} else {
				Yii::$app->session->setFlash('error', Yii::t('app', 'Can not be reverted'));
				Yii::info("\n" . '<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< REVERT ENDED WITH ERROR >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', 'translate_log');
			}
			if ($success) {
				$transaction->commit();
			}
//            return $this->redirect('index');
		} catch (Exception $e) {
			//TODO write to logs
			Yii::info($e->getMessage(), 'translate_log');
			Yii::$app->session->setFlash('error', 'has not reverted');
			$transaction->rollBack();
		}
		return $this->redirect('index');
	}

	public function actionFixMessages()
	{
		if (SourceMessage::fixMessages()) {
			Yii::$app->session->setFlash('success', 'yes');
		} else {
			Yii::$app->session->setFlash('error', 'no');
		}
		return $this->redirect('index');
	}
}
