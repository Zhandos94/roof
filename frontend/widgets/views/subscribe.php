<?php
use demogorgorn\ajax\AjaxSubmitButton;
?>
<style>
    #subscribe_success {
        color: green;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: bold;
    }
</style>
<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'subscribe',
    'enableAjaxValidation' => true,
    'validationUrl' => \yii\helpers\Url::to(['/validate/subscribe']),
]);
?>
<?=$form->field($model,'email')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Your email address'])->label(false) ?>

<?php
AjaxSubmitButton::begin([
    'label' => 'Notify Me',
    'useWithActiveForm' => 'subscribe',
    'ajaxOptions' => [
        'type' => 'POST',
        'success' => new \yii\web\JsExpression("function(data) {
                var div_name = $('#subscribe');
                if (document.getElementById('myAlert') == null) {
                    $(div_name).prepend('<div id=myAlert>Sended successfully</div>');
                    $('#subscribe')[0].reset();
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

<?php
\yii\bootstrap\ActiveForm::end();
?>