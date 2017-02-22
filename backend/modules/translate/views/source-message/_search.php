<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\translate\models\SourceMessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'language')->dropDownList($model::languages(), [
        'prompt' => Yii::t('app', 'Select language for filter')
    ]) ?>

    <?= $form->field($model, 'translation_type')->dropDownList($model::translationType(), [
        'prompt' => Yii::t('app', 'Select type of translation')
    ]) ?>

    <?= $form->field($model, 'id_from')->textInput() ?>

    <?= $form->field($model, 'id_to')->textInput() ?>

    <?= $form->field($model, 'translation')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), 'index', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
