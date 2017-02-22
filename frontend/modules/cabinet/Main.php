<?php

namespace frontend\modules\cabinet;

/**
 * cabinet module definition class
 */
class Main extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\cabinet\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setLayoutPath('@frontend/views/layouts');

        // custom initialization code goes here
    }
}
