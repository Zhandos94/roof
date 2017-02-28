<?php
/**
 * Created by PhpStorm.
 * User: mrdos
 * Date: 08.01.2017
 * Time: 20:44
 */
namespace frontend\components;

use yii;
use yii\base\Component;
use yii\helpers\BaseFileHelper;
use yii\helpers\Url;
use yii\base\ErrorException;

class Common extends Component
{

    const EVENT_NOTIFY = 'notify_admin';

    public function sendMail($subject, $text, $emailFrom = 'mr.dosik_kz@mail.ru', $nameFrom = 'Advert')
    {
//        if (\Yii::$app->mail->compose()
//            ->setFrom(['zhumagali.zh@yandex.ru' => 'Advert'])
//            ->setTo([$emailFrom => $nameFrom])
//            ->setSubject($subject)
//            ->setHtmlBody($text)
//            ->send()
//        ) {
//            $this->trigger(self::EVENT_NOTIFY);
//            return true;
//        }

      }

    public static function  Mail($subject, $mail, $text, $name)
    {
        try{
            $send_text = $text . 'С/У ' . $name;
            Yii::$app->mailer->compose()
                ->setFrom('zh.zhumagaly@gmail.com')
                ->setTo($mail)
                ->setSubject($subject)
                ->setTextBody($send_text)
                ->send();

            return true;
        } catch (ErrorException $e) {
            Yii::error($e->getMessage(), 'advert');
        }

    }

    public function notifyAdmin($event)
    {

        print "Notify Admin";
    }


    public static function afterSubstr($cut, $value)
    {
        if (!is_bool(strpos($value, $cut)))
            return substr($value, strpos($value, $cut) + strlen($cut));
    }

    public static function beforeSubstr($cut, $value)
    {
        return substr($value, 0, strpos($value, $cut));
    }

    public static function getTitle($data)
    {
        return $data['bedroom'] . ' Bed Room and ' . $data['kitchen'] . ' Kitchen Room Apartment on Sale';
    }

    public static function cutStr($text, $start = 0, $end = 100)
    {
        return mb_substr($text, $start, $end);
    }


    public static function getType($row)
    {
        return ($row['sold']) ? 'Sold' : 'New';
    }

    public static function getUrlAdvert($row)
    {
        return Url::to(['/main/main/view-advert', 'id' => $row['idadvert']]);
    }

    public static function getParseMoney($int)
    {
        $money = [];
        $int = array_reverse(str_split($int));

        $i = 0;
        foreach ($int as $value) {
            if ($i == 3) {
                $money[] = ',';
                $i = 0;
            }
            $money[] = $value;
            $i++;
        }
        $money = implode('', array_reverse($money)) . ' тг';

        return $money;
    }

    public static function getSliderImage($data, $general = true, $original = false)
    {
        $image = [];
        $base = Url::base();

        if ($general) {
            $image[] = $base . '/uploads/adverts/' . $data['idadvert'] . '/general/small_' . $data['general_image'];
        } else {
            $path = \Yii::getAlias("@frontend/web/uploads/adverts/" . $data['idadvert']);

            $files = BaseFileHelper::findFiles($path);
            foreach ($files as $file) {
                if (strstr($file, 'small_') && !strstr($file, 'general')) {
                    $image[] = $base . '/uploads/adverts/' . $data['idadvert'] . '/' . basename($file);
                }
            }
        }

        return $image;
    }


}