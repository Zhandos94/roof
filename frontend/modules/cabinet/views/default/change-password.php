<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 26.02.2017
 * Time: 17:13
 */
use demogorgorn\ajax\AjaxSubmitButton;

?>

<div class="advert-form">

    <?php $form = \yii\bootstrap\ActiveForm::begin(
        [
            'id' => 'password',
        ]
    ); ?>

    <?=$form->field($model,'password')->passwordInput() ?>
    <?=$form->field($model,'repassword')->passwordInput() ?>

    <?php
    AjaxSubmitButton::begin([
        'label' => 'Change password',
        'useWithActiveForm' => 'password',
        'ajaxOptions' => [
            'type' => 'POST',
            'success' => new \yii\web\JsExpression("function(data) {
                var div_name = $('#password');
                if (document.getElementById('myAlert') == null) {
                    $(div_name).prepend('<div id=myAlert>Saved successfully</div>');
                    $('#password')[0].reset();
                } else {
                    $('#myAlert').show()
                }
                setTimeout(function() { $('#myAlert').hide(); }, 3000);
            }"),
        ],
        'options' => ['class' => 'btn btn-success', 'type' => 'submit', 'id' =>'add-button'],
    ]);
    AjaxSubmitButton::end();
    ?>

    <?php \yii\bootstrap\ActiveForm::end() ?>


</div>
