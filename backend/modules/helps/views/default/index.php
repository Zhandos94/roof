<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\helps\models\HelpIntroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Help Intros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-intro-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Help Intro', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'page_id',
			'element_id',
			[
				'attribute' => 'source message',
				'value'     => 'sourceMessage.message'
			],
			[
				'attribute' => 'body',
				'value'     => 'messageModel.translation'
			],
			'position',
			'description',
			// 'variant_two',
			// 'is_guest',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
</div>
