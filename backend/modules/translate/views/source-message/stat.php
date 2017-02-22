<?php
/**
 * Created by IntelliJ IDEA.
 * User: Adlet
 * Date: 07.07.2016
 * Time: 12:15
 */

use backend\modules\translate\models\SourceMessage;

/* @var $this yii\web\View */

$this->title = 'Statistics';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="translate-source_message-stat">
    <div class="panel">
        <div class="panel-body">

            <table class="table  table-striped table-bordered" style="width: auto;">
                <tr>
                    <td>Не переведенные на русский:</td>
                    <td> <?= SourceMessage::getNotTranslatedMessagesNum('ru-RU') ?></td>
                </tr>
                <tr>
                    <td>Не переведенные на казахский:</td>
                    <td> <?= SourceMessage::getNotTranslatedMessagesNum('kz-KZ') ?></td>
                </tr>
                <tr>
                    <td>Не переведенные на английский:</td>
                    <td> <?= SourceMessage::getNotTranslatedMessagesNum('en-US') ?></td>
                </tr>
                <tr>
                    <td>С кириллицей:</td>
                    <td> <?= SourceMessage::getCyrillicNum() ?></td>
                </tr>
                <tr>
                    <td>Общее количество:</td>
                    <td> <?= SourceMessage::find()->count() ?></td>
                </tr>
            </table>

        </div>
    </div>
</div>
