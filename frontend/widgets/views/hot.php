<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 14.02.2017
 * Time: 15:05
 */
use frontend\components\Common;
?>

<div class="hot-properties hidden-xs">
    <h4>Hot Properties</h4>
    <div class="row">
        <?php foreach ($model as $row) { ?>
            <div class="col-lg-4 col-sm-5"><img src="<?= Common::getSliderImage($row)[0] ?>"  class="img-responsive img-circle" alt="properties"/></div>
            <div class="col-lg-8 col-sm-7">
                <h5><a href="<?=\yii\helpers\Url::toRoute(['/main/main/view-advert', 'id'=> $row['idadvert']])?>" ><?= Common::getTitle($row) ?> </a></h5>
                <p class="price"><?=$row['price'] . ' тг'?></p>
            </div>
        <?php } ?>
    </div>
</div>
