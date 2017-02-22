<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HelpIntro */

$this->title = 'Update Help Intro: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Help Intros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="help-intro-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
		'sourceMessage' => $sourceMessage,
		'message' => $message,
	]) ?>

</div>
