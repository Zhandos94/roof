<?php

namespace backend\modules\translate\models;

/**
 * This is the ActiveQuery class for [[ZetMessageArch]].
 *
 * @see ZetMessageArch
 */
class ZetMessageArchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ZetMessageArch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ZetMessageArch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
