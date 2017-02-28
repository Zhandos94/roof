<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 11.02.2017
 * Time: 10:50
 */
use demogorgorn\ajax\AjaxSubmitButton;
use frontend\components\Common;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Advert view';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
    <div class="properties-listing spacer">
    <div class="row">

        <div class="col-lg-3 col-sm-4 hidden-xs">

           <?= frontend\widgets\HotWidget::widget() ?>

            <div class="advertisement">
                <h4>Advertisements</h4>
                <img src="/images/advertisements.jpg"  class="img-responsive" alt="advertisement">
            </div>
        </div>

        <div class="col-lg-9 col-sm-8 ">
            <h2><?= Common::getTitle($model) ?></h2>
            <div class="row">
                <div class="col-lg-8">
                    <div class="property-images">
                        <!-- Slider Starts -->
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators hidden-xs">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <?php foreach (range(1, count($images) - 1) as $bt) {?>
                                    <li data-target="#myCarousel" data-slide-to="<?= $bt ?>" class=""></li>
                                <?php } ?>
                            </ol>
                            <div class="carousel-inner">
                                <!-- Item 1 -->
                                <div class="item active">
                                    <img src="<?= Common::getSliderImage($model)[0]?>"  class="properties" alt="properties" />
                                </div>
                                <!-- #Item N -->
                                <?php foreach ($images as $image) {?>
                                    <div class="item">
                                        <img src="<?= $image ?>"  class="properties" alt="properties" />
                                    </div>
                                <?php } ?>
                            </div>
                              <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                              <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div><!-- #Slider Ends -->
                    </div>
                    <div class="spacer"><h4><span class="glyphicon glyphicon-th-list"></span> Properties Detail</h4>
                        <p><?= $model->description ?></p>
                    </div>
                    <div><h4><span class="glyphicon glyphicon-map-marker"></span> Location</h4>
                        <div class="well">
                            <?= $map->display()?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="col-lg-12 col-sm-6">
                        <div class="property-info">
                            <p class="price"> <?= Common::getParseMoney($model->price) ?></p>
                            <p class="area"><span class="glyphicon glyphicon-map-marker"></span> <?= $model->address?> </p>
                            <div class="profile">
                            <span class="glyphicon glyphicon-user"></span> Agent Details
                            <p><?= $model->user->username ?><br> <?=$model->user->email ?></p>
                            </div>
                        </div>

                        <h6><span class="glyphicon glyphicon-home"></span> Availabilty</h6>
                        <div class="listing-detail">
                            <span data-toggle="tooltip" data-placement="bottom" data-original-title="Bed Room"><?= $model['bedroom'] ?></span>
                            <span data-toggle="tooltip" data-placement="bottom" data-original-title="Living Room"><?= $model['livingroom'] ?></span>
                            <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking"><?= $model['parking'] ?></span>
                            <span data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen"><?= $model['kitchen'] ?></span>
                        </div>

                    </div>
                    <div class="col-lg-12 col-sm-6 ">
                        <div class="enquiry">
                        <h6><span class="glyphicon glyphicon-envelope"></span> Post Enquiry</h6>
                            <?php $form = ActiveForm::begin([
                                    'id' => 'sendMessage',
                            ]
                            );?>
                                <?= $form->field($model_feeback, 'name')->textInput([
                                    'value' => $curent_user['name'], 'placeholder' => 'Name'])->label(false) ?>
                                <?= $form->field($model_feeback, 'email')->textInput([
                                    'value' => $curent_user['email'], 'placeholder' => 'Email'])->label(false) ?>
                                <?= $form->field($model_feeback, 'text')->textarea([
                                        'rows'=>6, 'placeholder' => 'Whats on your mind?' ])->label(false)?>
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => 'Send message',
                                'useWithActiveForm' => 'sendMessage',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'success' => new \yii\web\JsExpression("function(data) {
                                        console.log('asd');
                                        var div_name = $('.enquiry');
                                        if (document.getElementById('myAlert') == null) {
                                            $(div_name).prepend('<div id=myAlert>Mesage sended!</div>');
                                            $('#sendMessage')[0].reset();
                                        } else {
                                            $('#myAlert').show()
                                        }
                                        setTimeout(function() { $('#myAlert').hide(); }, 5000);
                                    }"),
                                                        ],
                                'options' => ['class' => 'btn btn-primary', 'type' => 'submit', 'id' =>'add-button'],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                </div>
            </div>`
        </div>
    </div>
    </div>
</div>

