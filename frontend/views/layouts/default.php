<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use frontend\assets\MainAsset;

MainAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=$this->title ?> </title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?=yii\helpers\Html::csrfMetaTags()?>
    <?php $this->head() ?>

</head>

<body>
<?php $this->beginBody() ?>

<?=$this->render("//common/head") ?>


<?=$content ?>



<?=$this->render("//common/footer") ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>



