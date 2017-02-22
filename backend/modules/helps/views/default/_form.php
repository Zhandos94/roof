<?php

use backend\modules\helps\models\HelpIntro;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use Yii;

/* @var $this yii\web\View */
/* @var $model HelpIntro */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $message \backend\modules\translate\models\Message */
/* @var $sourceMessage \backend\modules\translate\models\SourceMessage */

$script = <<< JS
	var curr_form = $('.help-intro-form').parent();

	$('#helpintro-page_id').keyup(function () {
		sourceMessageCustomValue();
	});

	$('#helpintro-element_id').keyup(function () {
		sourceMessageCustomValue();
	});

	function sourceMessageCustomValue () {
		if ($(curr_form).hasClass('help-intro-update')) {
			$('#sourcemessage-message').val($('#helpintro-page_id').val() + ' - ' + $('#helpintro-element_id').val());
		} else {
			$('#helpintro-message').val($('#helpintro-page_id').val() + ' - ' + $('#helpintro-element_id').val());
		}
	}
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>


<div class="help-intro-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'page_id')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'element_id')->textInput(['maxlength' => true]) ?>



	<?php
	if ($model->isNewRecord) {
		echo $form->field($model, 'message')->textInput(['maxlength' => true]);
	} else {
		echo $form->field($sourceMessage, 'message')->textInput(['maxlength' => true]);
	}
	?>

	<?php
	if ($model->isNewRecord) {
		echo $form->field($model, 'body')->textInput(['maxlength' => true]);
	} else {
		echo $form->field($message, 'translation')->textInput(['maxlength' => true]);
	}
	?>

	

	<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
