<?php

use backend\modules\translate\models\SourceMessage;
use backend\modules\translate\models\ZetTranslateData;
use common\models\User;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\translate\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\modules\translate\models\SourceMessage */
/* @var $cyrillic boolean */

$tempJS = <<<JS

$('#load-type').on('change',function(){
	$('#upload-excel').attr('action',$(this).val());
});

$('#checkbox-button').on('click',function(){
var keys = $('#grid-translate').yiiGridView('getSelectedRows');
$.ajax({
		cache: false,
		type: "POST",
		url: '/translate/source-message/delete-multiple',
		data: {'keys': keys}
	});
});

JS;
$this->registerJs($tempJS);

$this->title = Yii::t('app', 'Source Messages');
$this->params['breadcrumbs'][] = $this->title;

function showFilter($param, $translate)
{
	if (!empty(trim($param))) {
		echo $translate . ':' . $param . '&nbsp';
	}
}

$show_array_filter = function ($param, $translate) use ($searchModel) {

	/* @var $searchModel \backend\modules\translate\models\SourceMessageSearch */
	if (array_key_exists($param, $searchModel::translationType())) {
		echo $translate . ':' . $searchModel::translationType()[$param] . '&nbsp';
	}
}

?>
<div class="source-message-index">
	<div class="panel">
		<div class="panel-body">

			<h1><?= Html::encode($this->title) ?></h1>

			<div class="row">
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-2">
							<?= Html::a(Yii::t('app', 'With cyrillic'), ['index', 'cyrillic' => true],
								['class' => 'btn btn-raised btn-default']) ?>
						</div>
						<div class="col-md-3">
							<?= Html::a(Yii::t('app', 'Load from document'), ['create'],
								['data-target' => '#user-status', 'data-toggle' => 'modal', 'class' => 'btn btn-raised btn-primary']) ?>
						</div>
						<div class="col-md-3">
							<?= Html::a(Yii::t('app', 'Create Source Message'), ['create'], ['class' => 'btn btn-raised btn-success']) ?>
						</div>
						<div class="col-md-3">
							<?= Html::a(Yii::t('app', 'Statistic'), ['stat'], ['class' => 'btn btn-raised btn-info']) ?>
						</div>
						<div class="col-md-2">
							<?= Html::a(Yii::t('app', 'Revert'), '#',
								['data-target' => '#revert-modal', 'data-toggle' => 'modal', 'class' => 'btn btn-raised btn-info',
									'disabled' => ZetTranslateData::getLastVersion() === null ? true : false]) ?>
						</div>
						<div class="col-md-2">
							<?= Html::a(Yii::t('app', 'Fix'), ['fix-messages'], ['class' => 'btn btn-raised btn-default']) ?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<?= $this->render('_search', ['model' => $searchModel]); ?>
				</div>
			</div>

			<p>
				<?php
				if ($cyrillic) {
					echo Html::tag('h3', Yii::t('app', 'Text with cyrillic'));
				}
				?>
			</p>

			<h3><?= Yii::t('app', 'Export to document') ?></h3>
			<?= ExportMenu::widget([
				'dataProvider' => $dataProvider,
				'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
				'filename' => 'translates_' . Yii::$app->formatter->asDatetime(time(), 'yyyyMMdd_HHmm'),
				'columns' => [
					'id',
					'category:html',
					'message:html',
					[
						'format' => 'html',
						'label' => 'kz',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return $model->getTranslations('kz-KZ');
						},
					],
					[
						'format' => 'html',
						'label' => 'ru',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return $model->getTranslations('ru-RU');
						}
					],
					[
						'format' => 'html',
						'label' => 'en',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return $model->getTranslations('en-US');
						}
					],
				],
				'target' => '_self',
				'showConfirmAlert' => false,
				'fontAwesome' => true,

			]) . "<hr>\n" ?>

			<h2><?= Yii::t('app', 'Filter') ?></h2>
			<p>
				<?php
				showFilter($searchModel->language, Yii::t('app', 'filter-language'));
				$show_array_filter($searchModel->translation_type, Yii::t('app', 'filter-translation_type'));
				showFilter($searchModel->id_from, Yii::t('app', 'filter-id_from'));
				showFilter($searchModel->id_to, Yii::t('app', 'filter-id_to'));
				showFilter($searchModel->translation, Yii::t('app', 'filter-translation'));
				?>
			</p>

			<?= Html::button(Yii::t('app', 'Delete selected items'), ['id' => 'checkbox-button', 'class' => 'btn btn-default']) ?>

			<?= GridView::widget([
				'id' => 'grid-translate',
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\CheckboxColumn'],
					[
						'attribute' => 'id',
						'value' => 'id',
						'contentOptions' => function () {
							return ['style' => 'width:60px'];
						}
					],
					[
						'attribute' => 'category',
						'value' => 'category',
						'contentOptions' => function () {
							return ['style' => 'width:50px'];
						}
					],
					[
						'attribute' => 'message',
						'format' => 'raw',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return Html::a($model->message, ['update', 'id' => $model->id]);
						},
						'contentOptions' => function ($model) {
							if (SourceMessage::checkToCyrillic($model)) {
								return ['style' => 'background-color:red'];
							} else {
								return [];
							}
						}
					],
					[
						'attribute' => 'KZ',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return $model->getTranslations('kz-KZ');
						},
						'contentOptions' => function ($model) {
							/* @var SourceMessage $model */
							if (empty($model->getTranslations('kz-KZ'))) {
								return ['style' => 'background-color:orange'];
							} else {
								return [];
							}
						}
					],
					[
						'attribute' => 'RU',
						'value' => function ($model) {
							/* @var SourceMessage $model */
							return $model->getTranslations('ru-RU');
						},
						'contentOptions' => function ($model) {
							/* @var SourceMessage $model */
							if ($model->translatedStatus('ru-RU')) {
								return [];
							} else {
								return ['style' => 'background-color:red'];
							}
						}
					],

//                ['class' => 'yii\grid\ActionColumn'],
				],
			]); ?>

		</div>
	</div>
</div>

<?php Modal::begin([
	'id' => 'user-status',
	'header' => Yii::t('app', 'Load excel file')
]); ?>
<?= Html::dropDownList('Excel upload type', null, ['/translate/source-message/load-excel' => Yii::t('app', 'Replace NOT'),
	'/translate/source-message/load-excel?replace=true' => Yii::t('app', 'Replace')],
	['id' => 'load-type', 'class' => 'form-control',
		'prompt' => Yii::t('app', 'Please select type of upload translate from excel')]) ?>
<br>

<?php $model = new SourceMessage();
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'upload-excel'],
	'action' => '/translate/source-message/load-excel']); ?>

<?= $form->field($model, 'excelFile')->widget(FileInput::className(), [
	'options' => [
		'accept' => 'other',
	],
	'pluginOptions' => [
		'showPreview' => false,
		'showUpload' => false,
	]
]) ?>

<div class="form-group">
	<?= Html::submitButton(Yii::t('app', 'Load'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<?php Modal::begin([
	'id' => 'revert-modal',
	'header' => Yii::t('app', 'Revert translation')
]); ?>

<div class="alert alert-info">
	<?php $last_version = ZetTranslateData::getLastVersion();
	if ($last_version !== null) {
		/* @var ZetTranslateData $zet_data_model */
		$zet_data_model = ZetTranslateData::find()->where(['version' => $last_version])->andWhere(['reverted' => 0])->one();
		$text = Yii::t('app', 'user - {User}, version - {Version}, date - {Date}',
			['User' => User::findOne($zet_data_model->created_by)->username, 'Version' => $zet_data_model->version,
				'Date' => $zet_data_model->created_at]);
	} else {
		$text = Yii::t('app', 'Not have backup copies');
	}
	echo $text;
	?>
</div>

<div class="modal-footer">
	<?= Html::button(Yii::t('app', 'Modal window cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>

	<?= Html::a(Yii::t('app', 'Revert'), ['revert'], ['class' => 'btn btn-primary']) ?>
</div>

<?php Modal::end(); ?>
