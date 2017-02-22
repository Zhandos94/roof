<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>

<div class="row register">
    <br>
    <br>
    <br>


    <?php if(Yii::$app->session->hasFlash('success')): ?>

        <?php
        $success = Yii::$app->session->getFlash('success');

        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-info'
            ],
            'body' => $success
        ])
        ?>
    <?php
        endif;
    ?>

    <div class="col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 ">

        <?php $form = \yii\bootstrap\ActiveForm::begin([
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
        ]); ?>
        <?= $form->field($model, 'username');?>
        <?= $form->field($model, 'email');?>
        <?= $form->field($model, 'password')->passwordInput()?>
        <?= $form->field($model, 'repassword')->passwordInput()?>

        <?= \yii\helpers\Html::submitButton('Register', ['class' => 'btn btn-success'])?>

        <?php \yii\bootstrap\ActiveForm::end(); ?>


    </div>

</div>