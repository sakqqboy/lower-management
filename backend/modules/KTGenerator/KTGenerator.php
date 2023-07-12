<?php

namespace backend\modules\KTGenerator;

/**
 * kt-generator module definition class
 */
class KTGenerator extends \yii\base\Module
{
    public $newFileMode = 0666;
    public $newDirMode = 0777;
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\KTGenerator\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
