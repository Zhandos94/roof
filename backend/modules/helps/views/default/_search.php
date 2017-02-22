<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\helps\models\HelpIntroSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="help-intro-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'page_id') ?>

    <?= $form->field($model, 'element_id') ?>

    <?= $form->field($model, 'body') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'variant_two') ?>

    <?php // echo $form->field($model, 'is_guest') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
