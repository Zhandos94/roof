<style>
    .upload-images {
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <?php $form = \yii\bootstrap\ActiveForm::begin(
        ['options' => ['enctype' => 'multipart/form-data']]);?>
    <div class="row">
        <div class="col-lg-10">
            <?php
            echo $form->field($model, 'general_image')->widget(\kartik\file\FileInput::classname(),[
                'options' => [
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'uploadUrl' => \yii\helpers\Url::to(['file-upload-general']),
                    'deleteUrl' => \yii\helpers\Url::to(['delete-image']),

                    'uploadExtraData' => [
                        'advert_id' => $model->idadvert,
                    ],
                    'allowedFileExtensions' =>  ['jpg', 'png','gif'],
                    'initialPreview' => $image,
                    'initialPreviewAsData'=>true,
                    'initialPreviewConfig' => [
                        ['caption' => 'General Image', 'size' =>'2854553'],
                    ],
                    'overwriteInitial'=>false,
                    'showUpload' => true,
                    'showRemove' => false,
                    'dropZoneEnabled' => false
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 upload-images">
            <?php
            echo \yii\helpers\Html::label('Images');
            echo \kartik\file\FileInput::widget([
                'name' => 'images',
                'options' => [
                    'accept' => 'image/*',
                    'multiple'=>true
                ],
                'pluginOptions' => [
                    'uploadUrl' => \yii\helpers\Url::to(['file-upload-images']),
                    'uploadExtraData' => [
                        'advert_id' => $model->idadvert,
                    ],
                    'overwriteInitial' => false,
                    'allowedFileExtensions' =>  ['jpg', 'png','gif'],
                    'initialPreview' => $images_add,
                    'initialPreviewAsData'=>true,
                    'overwriteInitial'=>false,
                    'showUpload' => true,
                    'showRemove' => false,
                    'dropZoneEnabled' => false
                ]
            ]);
            ?>
        </div>
    </div>

<!--    <div class="row">-->
<!--        <div class="col-lg-10">-->
<!--            --><?php
//            echo $form->field($model, 'images[]')->widget(\kartik\file\FileInput::classname(),[
//                'options' => [
//                    'accept' => 'image/*',
//                    'multiple' => true,
//                ],
//                'pluginOptions' => [
//                    'uploadUrl' => \yii\helpers\Url::to(['file-upload-images    ']),
//                    'uploadExtraData' => [
//                        'advert_id' => $model->idadvert,
//                    ],
//                    'allowedFileExtensions' =>  ['jpg', 'png','gif'],
//                    'initialPreview' => $images_add,
//                    'initialPreviewAsData'=>true,
//                    'overwriteInitial'=>false,
//                    'showUpload' => true,
//                    'showRemove' => false,
//                    'dropZoneEnabled' => false
//                ]
//            ]);
//            ?>
<!--        </div>-->
<!--    </div>-->
    <div class="form-group">
        <?= \yii\helpers\Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>
<?php

$js = <<<JS
window.onload = function() {
    var src;
    var div_id;
    $( ".file-preview-initial" ).mouseenter(function() {
        src = $( this ).find( ".kv-file-content img" ).attr('src');
        div_id = $(this).attr('id');
        // console.log(src);
    });

    $('.kv-file-remove').on('click', function(event) {
        $.ajax({
            url: 'delete-upload-image',
            type: 'post',
            data: {img:src},

            success:function(data) {
                if(data) {
                    $('#' + div_id).remove();
                }
            }
        });
    });
}
JS;
$this->registerJs($js);

?>
