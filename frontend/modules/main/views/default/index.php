<?php
use frontend\components\Common;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="">

    <div id="slider" class="sl-slider-wrapper">
        <div class="sl-slider">
            <?php foreach ($slider_data as $row) { ?>
                <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                    <div class="sl-slide-inner">
                        <div class="bg-img" style="background-image: url('<?= '../'. Common::getSliderImage($row)[0] ?>')"></div>*/
                    </div>
                    <h2><a href="<?= Common::getUrlAdvert($row)?>"> <?= Common::getTitle($row) ?></a></h2>
                    <blockquote>
                        <p class="location"><span class="glyphicon glyphicon-map-marker"></span> <?= $row['address'] ?> </p>
                        <p> <?= Common::cutStr($row['description'])?></p>
                        <cite><?= Common::getParseMoney($row['price']) ?> </cite>
                    </blockquote>
                </div>
            <?php } ?>
        </div><!-- /sl-slider -->

        <nav id="nav-dots" class="nav-dots">
            <?php if ($slider_data_count >= 1) { ?>
                <span class="nav-dot-current"></span>
            <?php }  ?>
            <?php if ($slider_data_count > 1 ) { ?>
                <?php foreach (range(2, $slider_data_count) as $line) { ?>
                    <span></span>
                <?php } ?>
            <?php }?>
        </nav>

    </div><!-- /slider-wrapper -->
</div>



<div class="banner-search">
    <div class="container"><!-- banner -->
        <h3>Buy, Sale & Rent</h3>
        <div class="searchbar">
            <div class="row">

                <div class="col-lg-6 col-sm-6">
                <?= \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to('/main/main/find/'),'get') ?>
                    <?=Html::textInput('propert', '', ['class' => 'form-control']) ?>
                    <div class="row">
                        <div class="col-lg-3 col-sm-4">
                            <?=Html::dropDownList('price', '',[
                                '150000-200000' => '$150,000 - $200,000',
                                '200000-250000' => '$200,000 - $250,000',
                                '250000-300000' => '$250,000 - $300,000',
                                '300000' =>'$300,000 - above',
                            ],['class' => 'form-control', 'prompt' => 'Price']) ?>
                        </div>
                        <div class="col-lg-3 col-sm-4">

                            <?=Html::dropDownList('apartment', '',[
                                'Apartment',
                                'Building',
                                'Office Space',
                            ],['class' => 'form-control', 'prompt' => 'Property']) ?>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <?=Html::submitButton('Find Now', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                <?=\yii\bootstrap\Html::endForm()?>
                </div>

                <?php if(Yii::$app->user->isGuest) { ?>
                    <div class="col-lg-5 col-lg-offset-1 col-sm-6 ">
                        <p>Join now and get updated with all the properties deals.</p>
                        <button class="btn btn-info"   data-toggle="modal" data-target="#loginpop">Login</button>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div><!-- End banner -->
<div class="container">
    <div class="properties-listing spacer"> <a href="buysalerent.html"  class="pull-right viewall">View All Listing</a>
        <h2>Featured Properties</h2>
        <div id="owl-example" class="owl-carousel">
            <?php foreach ($property as $row) { ?>
                <div class="properties">
                    <div class="image-holder">
                        <img src="<?= Common::getSliderImage($row)[0]?>"  class="img-responsive" alt="properties">
                        <div class="status <?= ($row['sold']) ? 'new' : 'sold' ?>"><?= ($row['sold']) ? 'sold' : 'new' ?></div>
                    </div>
                    <h4><a href="<?= Url::toRoute(['/main/main/view-advert', 'id' => $row['idadvert']])?>"> <?= Common::getTitle($row) ?> </a></h4>
                    <p class="price"><?= Common::getParseMoney($row['price']) ?></p>
                    <div class="listing-detail">
                        <span data-toggle="tooltip" data-placement="bottom" data-original-title="Bed Room"> <?= $row['bedroom']?> </span>
                        <span data-toggle="tooltip" data-placement="bottom" data-original-title="Living Room"> <?= $row['livingroom'] ?> </span>
                        <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking"> <?= $row['parking'] ?> </span>
                        <span data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen"> <?= $row['kitchen'] ?> </span>
                    </div>
                    <a class="btn btn-primary" href="<?=\yii\helpers\Url::toRoute(['/main/main/view-advert', 'id'=> $row['idadvert']])?>" >View Details</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="spacer">
        <div class="row">
            <div class="col-lg-6 col-sm-9 recent-view">
                <h3>About Us</h3>
                <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<br><a href="about.html" >Learn More</a></p>

            </div>
            <div class="col-lg-5 col-lg-offset-1 col-sm-3 recommended">
                <h3>Recommended Properties</h3>
                <div id="myCarousel" class="carousel slide">
                    <ol class="carousel-indicators">
                        <?php if ($recommend_count >= 1 ) { ?>
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <?php } ?>
                        <?php if($recommend_count > 1) { ?>
                            <?php foreach (range(1, $recommend_count - 1) as $count) { ?>
                                <li data-target="#myCarousel" data-slide-to="<?= $count ?>" class=""></li>
                            <?php } ?>
                        <?php } ?>
                    </ol>
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <?php
                            $i = 0;
                            foreach($recommend as $row) {
                        ?>
                            <div class="item <?= ($i == 0) ? 'active' : '' ?>">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <img src="<?= Common::getSliderImage($row)[0]?>"  class="img-responsive" alt="properties"/>
                                    </div>
                                    <div class="col-lg-8">
                                        <h5><a href="<?= Url::toRoute(['/main/main/view-advert', 'id' => $row['idadvert']])?>"> <?= Common::getTitle($row) ?></a></h5>
                                        <p class="price">
                                            <?= Common::getParseMoney($row['price']) ?>
                                        </p>
                                        <a href="<?= Url::toRoute(['/main/main/view-advert', 'id' => $row['idadvert']])?>"  class="more">More Detail</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $i++;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
