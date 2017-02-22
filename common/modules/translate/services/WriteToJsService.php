<?php
/**
 * Created by BADI.
 * DateTime: 17.11.2016 18:14
 */

namespace common\modules\translate\services;


use Yii;

class WriteToJsService
{


    public function execute()
    {
        $fileName = Yii::getAlias('@frontend') . '/web/js/translate/message.js';
        $recordsArray = Yii::$app->db->createCommand('
                                                select * from source_message sm
                                                left join message m
                                                on m.id=sm.id
                                                where category=\'_js\'
                                                    ')->queryAll();
        $newArray = [];
        foreach ($recordsArray as $record) {
            $newArray[$record['language']][$record['message']] = $record['translation'];//change structure of array
        }
        if(file_exists($fileName)){
            $date = date('ymHi');
            rename($fileName, $fileName . $date);
        }
        $file = fopen($fileName, "w+");
        foreach ($newArray as $key => $item) {
            $varName = substr($key, 0, 2);
            fwrite($file, "var {$varName} = " . json_encode($item) . ';');
        }
        fclose($file);
    }


    /*public function execute1()
    {
        Yii::$app->db->createCommand('
                                    SELECT kz, ru, en, message
                                    FROM
                                    (
                                    SELECT *
                                    FROM source_message
                                    LEFT JOIN
                                    (
                                    SELECT kz, ru, en, id1
                                    FROM (
                                    SELECT *
                                    FROM(
                                    SELECT translation AS ru, id AS id_ru
                                    FROM message
                                    WHERE LANGUAGE=\'ru-RU\'
                                    ) m1
                                    LEFT JOIN (
                                    SELECT translation AS kz, id AS id_kz
                                    FROM message
                                    WHERE LANGUAGE=\'kz-KZ\'
                                    ) m2 ON m1.id_ru=m2.id_kz
                                    ) m
                                    LEFT JOIN (
                                    SELECT translation AS en, id AS id1
                                    FROM message
                                    WHERE LANGUAGE=\'en-US\'
                                    ) m3 ON m.id_ru=m3.id1
                                    ) message ON message.id1=source_message.id
                                    WHERE category=\'seo\'
                                    ) mm
        ')->queryAll();
    }*/

}