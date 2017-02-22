<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HelpIntro */

$this->title = 'Create Help Intro';
$this->params['breadcrumbs'][] = ['label' => 'Help Intros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="help-intro-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
