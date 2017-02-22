<?php

namespace backend\modules\helps\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\helps\models\HelpIntro;

/**
 * HelpIntroSearch represents the model behind the search form about `common\models\HelpIntro`.
 */
class HelpIntroSearch extends HelpIntro
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'is_guest'], 'integer'],
			[['page_id', 'element_id', 'body', 'description', 'position', 'variant_two'], 'safe'],
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
	public function search($params)
	{
		$query = HelpIntro::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'is_guest' => $this->is_guest,
		]);

		$query->andFilterWhere(['like', 'page_id', $this->page_id])
			->andFilterWhere(['like', 'element_id', $this->element_id])
			->andFilterWhere(['like', 'body', $this->body])
			->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['like', 'position', $this->position])
			->andFilterWhere(['like', 'variant_two', $this->variant_two]);

		return $dataProvider;
	}
}
