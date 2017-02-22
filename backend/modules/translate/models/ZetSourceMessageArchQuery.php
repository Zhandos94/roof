<?php

namespace backend\modules\translate\models;

/**
 * This is the ActiveQuery class for [[ZetSourceMessageArch]].
 *
 * @see ZetSourceMessageArch
 */
class ZetSourceMessageArchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ZetSourceMessageArch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ZetSourceMessageArch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
