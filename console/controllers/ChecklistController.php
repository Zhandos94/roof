<?php
/**
 * User: TOLK   created by IntelliJ IDEA.
 * Date: 11.02.2016 12:18
 */
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * @property string info
 * @property string warning
 */
class ChecklistController extends Controller
{

    const DT_FORMAT = 'Y-m-d H:i:s';
    private $infoTxt;
    private $warnTxt;
    private $warncount = 0;
    private /** @noinspection PhpUnusedPrivateFieldInspection */
        $errorTxt;

    public function actionIndex()
    {
        //error_reporting(E_ALL ^ E_NOTICE);

        //example colorize:
        //$name = $this->ansiFormat('TOLK', Console::BG_GREEN, Console::FG_YELLOW);
        //echo "Hello, my name is $name.";

        $startTime = time();
        echo "checklist STARTed, on " . date(self::DT_FORMAT, $startTime)
            . "\t\t\tCurrent PHP version: " . phpversion();
        //echo var_dump(Yii::$app);

        $this->iniCheck(true);

//        $this->yiiCheck();

        if ($this->hasWarning()) {
            echo "\nChecklist has WARNING!!!";
            echo $this->warnTxt . "\n";
        }

        echo $this->infoTxt;
    }

    private function yiiCheck(/*$detail = false*/) {
        $this->info = 'Host info - ' . Yii::$app->urlManager->hostInfo
            . "\t\tBase url - " . @Yii::$app->urlManager->baseUrl;
    }

    private function iniCheck($detail = false)
    {
        if ($detail) {
            $iniPath = php_ini_loaded_file();
            $this->info = "Ini file - {$iniPath}";
        }


        $iniTimezone = ini_get('date.timezone');
        if ($iniTimezone == '') {
            $this->warning = 'Timezone in ini file is blank, some packages set wrong if blank value(eg. MPDF)';
        } else {
            $this->info = "Timezone in ini file - {$iniTimezone}";
        }

        if ($detail) {
            $iniErrReporting = ini_get('error_reporting');
            $this->info = "Error reporting in ini file - {$iniErrReporting}";
        }

        $this->info = "";
    }

    private function hasWarning()
    {
        return trim($this->warnTxt) != '';
    }

    protected function setWarning($str)
    {
        $this->warncount++;
        $this->warnTxt .= "\n$this->warncount) $str";
    }

    protected function setInfo($str)
    {
        $this->infoTxt .= "\n$str";
    }
}