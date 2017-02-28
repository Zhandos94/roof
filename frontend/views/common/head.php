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
                        ['label' => 'About', 'url' => ['/main/main/page', 'view' => 'about']],
                        ['label' => 'Agent', 'url' => ['/main/main/page', 'view' => 'agent']],
                        ['label' => 'Contact', 'url' => Url::to(['/main/main/contact'])],
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
            $menuItems = [];
            $guest = Yii::$app->user->isGuest;
            if($guest) {
                $menuItems[] =  ['label' => 'Login', 'url' => '#',
                    'linkOptions' => ['data-target' => '#loginpop', 'data-toggle' => "modal"]];
            }
            else{
                $menuItems[] =  ['label' => 'Manager adverts', 'url' => ['/cabinet/advert']];
                $menuItems[] =  ['label' => 'Settings', 'url' => ['/cabinet/default/settings']];
                $menuItems[] =  ['label' => 'Change Password', 'url' => ['/cabinet/default/change-password']];
                $menuItems[] = ['label' => 'Logout',  'url' => ['/main/main/logout'], 'linkOptions' => ['data-method' => 'post']];
            }
            echo Nav::widget([
                'options' => ['class' => 'pull-right'],
                'items' => $menuItems,
            ]);
        ?>
    </div>
    <!-- #Header Starts -->
</div>