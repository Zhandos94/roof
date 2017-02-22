<?php
use \yii\bootstrap\Nav;
use yii\helpers\Url;

?>
<!-- Header Starts -->
<div class="navbar-wrapper">

    <div class="navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">


                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
                <?php
                    $menuItems = [
                        ['label' => 'Home', 'url' => Url::to(['/main/default']), 'options' => ['class' => 'active']],
                        ['label' => 'About', 'url' => '#'],
                        ['label' => 'Agent', 'url' => '#'],
                        ['label' => 'Blog', 'url' => '#'],
                        ['label' => 'Cabinet', 'url' => Url::to(['/cabinet/advert'])],
                        ['label' => 'Login', 'url' => Url::to(['/main/main/login'])],
                        ['label' => 'logout', 'url' => Url::to(['/main/main/logout'])],
                        ['label' => 'Register', 'url' => Url::to(['/main/main/register'])],
                    ];
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'items' => $menuItems,
                    ]);
                ?>
            </div>
            <!-- #Nav Ends -->

        </div>
    </div>

</div>
<!-- #Header Starts -->

<div class="container">

    <!-- Header Starts -->
    <div class="header">
        <a href="<?= Url::to(['/main/default'])?>" ><img src="/images/logo.png"  alt="Realestate"></a>
            <?php
                $menuItems = [
                    ['label' => 'Buy', 'url' => '#'],
                    ['label' => 'Sale', 'url' => '#'],
                    ['label' => 'Rent', 'url' => '#'],
                ];
                echo Nav::widget([
                   'options' => ['class' => ['pull-right']],
                   'items' => $menuItems,
                ]);
            ?>
    </div>
    <!-- #Header Starts -->
</div>