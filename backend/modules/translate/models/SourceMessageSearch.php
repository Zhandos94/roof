<?php

namespace backend\modules\translate\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SourceMessageSearch represents the model behind the search form about `backend\modules\translate\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    public $translation_type;
    public $translation;
    public $id_from;
    public $id_to;
    public $language;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'translation_type', 'id_from', 'id_to'], 'integer'],
            [['category', 'message', 'language', 'translation'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $cyrillic = false)
    {
        $query = SourceMessage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['>=', 'id', $this->id_from])
            ->andFilterWhere(['<=', 'id', $this->id_to])
            ->andFilterWhere(['in', 'id', $this->langMessage($this->language)])
            ->andWhere(['in', 'id', $this->similarMessage()])
            ->orderBy('id DESC');

        if($cyrillic){
            $query->andWhere(['in', 'id', $this->getCyrillicId()]);
        } else {
            $query->andWhere(['not in', 'id', $this->getCyrillicId()]);
        }
        return $dataProvider;
    }

    public function similarMessage(){
        $id = [];
        $query = Message::find();
        if (!empty($this->translation)) {
            $query->where(['like', 'translation', $this->translation]);
        }
        $messages = $query->all();
        foreach ($messages as $m){
            $id[] = $m->id;
        }
        return $id;
    }

    public function langMessage($lang){
        $id = [];
        $message = Message::find()->where(['language' => $lang])->all();
        if ($this->translation_type == 0) {
            foreach ($message as $item) {
                if (empty(trim($item->translation))) {
                    $id[] = $item->id;
                }
            }
        } else {
            foreach ($message as $item) {
                if (!empty(trim($item->translation))) {
                    $id[] = $item->id;
                }
            }
        }
        return $id;
    }

    public function getCyrillicId()
    {
        $id = [];
        $source_message = SourceMessage::find()->all();
        /* @var $item SourceMessage */
        foreach ($source_message as $item) {
            if ($this->checkToCyrillic($item)) {
                $id[] = $item->id;
            }
        }
        return $id;
    }

    public static function getFilters()
    {
        $filters = [];
        return $filters;
    }

    public static function languages()
    {
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $query = Yii::$app->db->createCommand('
          SELECT DISTINCT language FROM message
          ')->queryColumn();
        $languages = [];
        foreach ($query as $q) {
            $languages[$q] = $q;
        }
        return $languages;
    }

    public static function translationType()
    {
        return [
            0 => Yii::t('app', 'not translated'),
            1 => Yii::t('app', 'translated'),
        ];
    }
}
