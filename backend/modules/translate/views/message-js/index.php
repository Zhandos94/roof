<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\models\Status;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Message Js');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="zet-message-js-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            array(
                'attribute'=> 'id',
                'header'=> 'id',
                'headerOptions' => ['style' => 'width:5%'],
            ),
            'message',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{add-message}',
                'buttons' => [
                    'add-message' => function ($url,$model,$key) {
                        return Html::a(Yii::t('app', 'Add'), $url);
                    },
                ],
            ],

        ],
    ]);


 ?>

</div>
