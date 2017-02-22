<?php

namespace backend\modules\translate\models;

use common\models\User;
use yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

//use backend\components\services\NameService;

/**
 * This is the model class for table "source_message".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 * @property string $kz
 * @property string $ru
 * @property string $en
 * @property integer $rpl_msg
 * @property integer $new_msg
 * @property integer $empty_msg
 * @property integer $revert_version
 */
class SourceMessage extends ActiveRecord
{
    const LANG_COUNT = 3;
    const DEFAULT_CATEGORY = 'app';
    const FILL_EMPTY = 10;
    const REPLACE = 0;
    const SKIP = 5;
    const SCENARIO_REVERT = 'revert';
    const SCENARIO_MESSAGE = 'saveMessage';

    public $kz;
    public $ru;
    public $en;
    public $rpl_msg = 0;
    public $new_msg = 0;
    public $empty_msg = 0;
    public $excelFile;
    public $revert_version = 0;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['category'], 'required', 'message' => Yii::t('app', 'Enter category or write "app"')],
            [['message'], 'string'],
            [['id'], 'integer', 'on' => 'revert'],
            [['category'], 'string', 'length' => 3],
            [['message', 'category'], 'validateFromCyrillic'],
            [['message', 'category'], 'string', 'on' => 'revert'],
            [['kz', 'ru', 'en'], 'safe'],
            [['ru'], 'required', 'message' => Yii::t('app', 'The field RU must not be empty'), 'on'=>self::SCENARIO_MESSAGE]
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REVERT] = ['id', 'message', 'category'];

        /***/
        $scenarios[self::SCENARIO_MESSAGE] = ['kz', 'ru', 'en', 'message'];
        /***/
        return $scenarios;
    }

    public function translatedStatus($lang)
    {
        /* @var Message $message */
        if (($message = $this->findMessage($lang)->one()) !== null) {
            if (empty(trim($message->translation))) {
                $text = false;
            } else {
                $text = true;
            }
        } else {
            $text = false;
        }
        return $text;
    }

    public static function getNotTranslatedMessagesNum($lang)
    {
        $i = 0;
        $message = Message::find()->where(['language' => $lang])->all();
        /* @var Message $item */
        foreach ($message as $item) {
            $parent = SourceMessage::findOne($item->id);
            if (!self::checkToCyrillic($parent)) {
                if (empty(trim($item->translation))) {
                    $i++;
                }
            }
        }
        return $i;
    }

    public static function getCyrillicNum()
    {
        $i = 0;
        $source_message = SourceMessage::find()->all();
        foreach ($source_message as $item) {
            if (self::checkToCyrillic($item)) {
                $i++;
            }
        }
        return $i;
    }

    public static function checkToCyrillic($model)
    {
        return preg_match('/[а-яА-ЯёЁ]/iu', $model->message);
    }

    public function saveTranslates()
    {
        if (!$this->writeTranslates('kz-KZ', $this->kz)
            || !$this->writeTranslates('ru-RU', $this->ru)
            || !$this->writeTranslates('en-US', $this->en)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Function accept data and fill or create Message.
     * @param string $language
     * @param string $translation
     * @return boolean
     */
    public function writeTranslates($language, $translation)
    {
        /* @var Message $message */
        $message = $this->getMessage($language);
        $message->translation = $translation;
        return $message->save();
    }

    public function existing_translates()
    {
        $_params = \Yii::$app->controller->module->params;
        $msg_kz = $this->getMessage($_params['lang_kz']);
        $this->kz = $msg_kz->translation;
        $msg_ru = $this->getMessage($_params['lang_ru']);
        $this->ru = $msg_ru->translation;
        $msg_en = $this->getMessage($_params['lang_en']);
        $this->en = $msg_en->translation;
    }

    public function getTranslations($lang)
    {
        /* @var $message_model Message */
        $message_model = $this->findMessage($lang)->one();

        if ($message_model !== null) {
            return $message_model->translation;
        } else {
//            self::writeErrors($this->message, $this->category, $this->id);
            return ''; //'Error321-'.$lang.'-'.$this->id;
        }
    }

    /**
     * @param $language string
     * @return Message
     */
    public function getMessage($language)
    {
        $message = $this->findMessage($language)->one();
        if ($message === null) {
            $message = new Message();
            $message->language = $language;
            $message->id = $this->id;
        }
        return $message;
    }

    /**
     * @param string $language
     * @return yii\db\ActiveQuery
     */
    public function findMessage($language)
    {
        return Message::find()->where(['id' => $this->id])->andWhere(['language' => $language]);
    }

    /**
     * @param $replace boolean
     * @param $file resource
     * @return boolean
     */
    public function saveFromExcel($replace = false, $file)
    {
        $fill_msg = 0;
        $new_msg = 0;
        $rpl_msg = 0;
        $_params = \Yii::$app->controller->module->params;
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $_col_msg_category = $_params['message_category'];
//        $_col_msg = $_params['message'];
        foreach ($sheetData as $item) {
            if (trim($item[$_params['message_id']]) == 'ID' || trim($item[$_params['message_id']]) == 'Номер') {
                continue;
            }
            $category = trim($item[$_col_msg_category]) == '' ? self::DEFAULT_CATEGORY
                : $item[$_col_msg_category];
            $message = $item[$_params['message']];
            if (trim(empty($message)) || !preg_match('/[a-zA-Z]/i', $message) || !preg_match('/[a-zA-Z]/i', $category)) {
                continue;
            }
            if (empty(trim($item[$_params['message_kz']]))
                && empty(trim($item[$_params['message_ru']]))
                && empty(trim($item[$_params['message_en']]))
            ) {
                continue;
            }
            $lang_messages = [
                $_params['lang_kz'] => $item[$_params['message_kz']],
                $_params['lang_ru'] => $item[$_params['message_ru']],
                $_params['lang_en'] => $item[$_params['message_en']],
            ];
            /* @var SourceMessage $src_msg */
            $src_msg = SourceMessage::find()->where(['like binary', 'message', $message])
                ->andWhere(['like binary', 'category', $category])->one();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $success = true;
                if ($src_msg !== null) {
                    if ($replace) {
                        $result_fill = $src_msg->saveTranslateFromExcel($lang_messages);
                        $new_msg += $result_fill[0];
                        $fill_msg += $result_fill[1];
                        $rpl_msg += $result_fill[2];
                        if (($result_fill[0] + $result_fill[1] + $result_fill[2] + $result_fill[3]) != self::LANG_COUNT) {
                            self::writeErrors($message, $category, $src_msg->id);
                            $success = false;
                        }
                    } else {
                        $result_empty = $src_msg->fillEmptyTranslations($lang_messages);
                        $new_msg += $result_empty[0];
                        $fill_msg += $result_empty[1];
                        if (($result_empty[0] + $result_empty[1] + $result_empty[2]) != self::LANG_COUNT) {
                            self::writeErrors($message, $category, $src_msg->id);
                            $success = false;
                        }
                    }
                } else {
                    $src_msg = new SourceMessage();
                    $src_msg->category = $category;
                    $src_msg->message = $message;
                    if ((int)$item[$_params['message_id']] != 0) {
                        if (($any_src_msg = SourceMessage::findOne((int)$item[$_params['message_id']])) === null) {
                            $src_msg->id = (int)$item[$_params['message_id']];
                        }
                    }
                    if ($src_msg->save()) {
                        $result_new = $src_msg->newMsgTranslates($src_msg->id, $lang_messages);
                        $new_msg += $result_new;
                        if ($result_new != self::LANG_COUNT) {
                            self::writeErrors($message, $category, $src_msg->id);
                            $success = false;
                        }
                    } else {
                        self::writeErrors($message, $category);
                        $success = false;
                    }
                }
                if ($success) {
                    $transaction->commit();
                } else {
                    return false;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        Yii::$app->getSession()->setFlash('success',
            'Создано: ' . $new_msg . '<br>Заменено: ' . $rpl_msg . '<br>Найдено пустых: ' . $fill_msg);
        return true;
    }

    /**
     * Replace translations
     * @param array $lang_messages
     * @return array
     */
    public function saveTranslateFromExcel(array $lang_messages)
    {
        $_replaced = 0;
        $_new = 0;
        $_filled = 0;
        $_missed = 0;
        foreach ($lang_messages as $lang => $translation) {
            if ($this->isEmpty($translation)) {
                $translation = null;
            } else {
                $translation = html_entity_decode($translation, ENT_QUOTES);
            }
            /* @var Message $msg */
            if (($msg = $this->findMessage($lang)->one()) !== null) {
                $translation = trim($translation);
                $msg->translation = trim($msg->translation);
                if (!$this->isEmpty($translation)) {
                    if ($this->isEmpty($msg->translation)) {
                        $count_type = self::FILL_EMPTY;
                    } else {
                        if ($msg->translation == $translation) {
                            $count_type = self::SKIP;
                        } else {
                            $count_type = self::REPLACE;
                        }
                    }
                    $msg->translation = trim($translation);
                    if ($msg->save()) {
                        if ($count_type == self::SKIP) {
                            $_missed++;
                        } else {
                            $count_type == self::FILL_EMPTY ? $_filled++ : $_replaced++;
                        }
                    }
                } else {
                    $_missed++;
                }
            } else {
                if ($this->createOneMessage($lang, $translation)) {
                    $_new++;
                }
            }
        }
        return [$_new, $_filled, $_replaced, $_missed];
    }

    /**
     * Creates a new Message model.
     * @param integer $id
     * @param array $lang_messages
     * @return integer
     */
    public function newMsgTranslates($id, array $lang_messages)
    {
        $_new = 0;
        foreach ($lang_messages as $lang => $translation) {
            $msg = new Message();
            $msg->id = $id;
            $msg->language = $lang;
            if ($this->isEmpty($translation)) {
                $msg->translation = null;
            } else {
                $msg->translation = html_entity_decode($translation, ENT_QUOTES);
            }
            if ($msg->save()) {
                $_new++;
            }
        }
        return $_new;
    }

    /**
     * Fills Message model if not have translation.
     * @param array $lang_messages
     * @return array
     */
    public function fillEmptyTranslations(array $lang_messages)
    {
        $_new = 0;
        $_fill = 0;
        $_missed = 0;
        foreach ($lang_messages as $lang => $translation) {
            /* @var Message $msg */
            $msg = $this->findMessage($lang)->one();
            if ($msg !== null) {
                if (!$this->isEmpty($translation)
                    && $this->isEmpty($msg->translation)
                ) {
                    $msg->translation = html_entity_decode($translation, ENT_QUOTES);
                    if ($msg->save()) {
                        $_fill++;
                    }
                } else {
                    $_missed++;
                }
            } else {
                if ($this->createOneMessage($lang, $translation)) {
                    $_new++;
                }
            }
        }
        return [$_new, $_fill, $_missed];
    }

    /**
     * @param $language string
     * @param $translation string
     * @return boolean
     */
    public function createOneMessage($language, $translation)
    {
        $message = new Message();
        $message->id = $this->id;
        $message->language = $language;
        $message->translation = $translation;
        return $message->save();
    }

    /**
     * @param $translation string
     * @return boolean
     */
    public function isEmpty($translation)
    {
        $translation = trim($translation);
        $empty_values = ['', '(not set)', '(не задано)'];
        if (in_array($translation, $empty_values)) {
            return true;
        }
        return false;
    }

    /**
     * This function is will be write to logs about errors with create or write to Message, include info about SourceMessage
     * @param $id integer
     * @param $message string
     * @param $category string
     */
    public static function writeErrors($message, $category, $id = null)
    {
        Yii::info([
            'id' => $id,
            'message' => $message,
            'category' => $category
        ], 'errors_log');
    }

    public static function createArchiveModels()
    {
        $version = ZetTranslateData::find()->max('version') + 1;
        /* @var SourceMessage $src_msg */
        foreach (SourceMessage::find()->each(10) as $src_msg) {
            /* @var ZetSourceMessageArch $src_msg_arch */
            $src_msg_arch = new ZetSourceMessageArch();
            if ($src_msg_arch->load(['ZetSourceMessageArch' => $src_msg->attributes])) {
                $src_msg_arch->version = $version;
                if (!$src_msg_arch->save()) {
                    Yii::info('src_msg archive not save' . $src_msg_arch->getErrors(), 'translate_log');
                    return false;
                }
            } else {
                Yii::info('src_msg archive not load' . $src_msg_arch->getErrors(), 'translate_log');
                return false;
            }
        }
        /* @var Message $message */
        foreach (Message::find()->each(10) as $message) {
            /* @var ZetMessageArch $msg_arch */
            $msg_arch = new ZetMessageArch();
            if ($msg_arch->load(['ZetMessageArch' => $message->attributes])) {
                $msg_arch->version = $version;
                if (!$msg_arch->save()) {
                    Yii::info($msg_arch->getErrors(), 'translate_log');
                    Yii::info($msg_arch->id, 'translate_log');
                    return false;
                }
            } else {
                Yii::info('msg archive not load' . $msg_arch->getErrors(), 'translate_log');
                return false;
            }
        }
        /* @var ZetTranslateData $zet_data_model */
        $zet_data_model = new ZetTranslateData();
        $zet_data_model->version = $version;
        $zet_data_model->created_by = Yii::$app->user->id;
        return $zet_data_model->save();
    }

    public static function revertModels()
    {
        $last_version = ZetTranslateData::getLastVersion();
        foreach (ZetSourceMessageArch::find()->where(['version' => $last_version])->each(10) as $src_msg_arch) {
            $source_message = new SourceMessage(['scenario' => self::SCENARIO_REVERT]);
            if ($source_message->load(['SourceMessage' => $src_msg_arch->attributes])) {
                if (!$source_message->save(false)) {
                    return false;
                }
            } else {
                return false;
            }
        }

        foreach (ZetMessageArch::find()->where(['version' => $last_version])->each(10) as $msg_arch) {
            $message = new Message();
            if ($message->load(['Message' => $msg_arch->attributes])) {
                if (!$message->save(false)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        /* @var ZetTranslateData $zet_data_model */
        $zet_data_model = ZetTranslateData::find()->where(['version' => $last_version])->andWhere(['reverted' => 0])->one();
        $zet_data_model->reverted = 1;
        if ($zet_data_model->save()) {
            /** @noinspection PhpUndefinedFieldInspection */
            Yii::info("\n" . '<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Reverted to version ' . $last_version . ' user - '
                . Yii::$app->user->identity->username . ' date - '
                . Yii::$app->formatter->asDatetime($zet_data_model->created_at, 'yyyy-MM-dd HH:mm') . '>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>',
                'translate_log');
            $CrUser = User::findOne($zet_data_model->created_by);
            Yii::$app->session->setFlash('success', 'Reverted to version ' . $last_version . ' user - '
                . ($CrUser !== null ? $CrUser->username : 'User not found') /*NameService::getUsername($zet_data_model->created_by)*/ . ' date - '
                . $zet_data_model->created_at);
        }
        return $zet_data_model->save();

    }

    public static function fixMessages()
    {
        $languages = ['kz-KZ', 'en-US', 'ru-RU'];
        foreach ($languages as $lang) {
            $source_messages = SourceMessage::find()->all();
            /* @var $src_message SourceMessage */
            foreach ($source_messages as $src_message) {
                $message = Message::find()->where(['id' => $src_message->id])->andWhere(['language' => $lang])->one();
                if ($message === null) {
                    $message = new Message();
                    $message->id = $src_message->id;
                    $message->language = $lang;
                    if (!$message->save()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function validateFromCyrillic($attribute){
        if(preg_match('/[а-яА-ЯёЁ]/iu', $this->$attribute)){
            $this->addError($attribute, Yii::t('app', 'Please entr only english words'));
        }
    }
}
