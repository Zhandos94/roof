<?php
/**
 * Created by BADI.
 * DateTime: 21.11.2016 17:20
 */

namespace common\services;


use Yii;

class GetIntColumn
{
	private $column;

	public function __construct($column)
	{
		if (is_string($column)) {
			$this->column = $column;
		}
	}


	public function execute() {
		$column = $this->column;
		$hash = Yii::$app->db->createCommand("select {$column} from int_settings where id=1")->queryScalar();
		return $hash;
	}

	public function test($file){
		return $file;
	}
}
