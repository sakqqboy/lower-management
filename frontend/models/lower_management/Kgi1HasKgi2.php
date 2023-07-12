<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\Kgi1HasKgi2Master;

/**
* This is the model class for table "kgi1_has_kgi2".
*
* @property integer $id
* @property integer $kgi1Id
* @property integer $kgi2Id
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi1HasKgi2 extends \frontend\models\lower_management\master\Kgi1HasKgi2Master{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return array_merge(parent::rules(), []);
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }
}
