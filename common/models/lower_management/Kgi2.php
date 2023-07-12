<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\Kgi2Master;

/**
* This is the model class for table "kgi2".
*
* @property integer $kgi2Id
* @property string $kgi2Name
* @property integer $branchId
* @property integer $teamId
* @property integer $teamPositionId
* @property string $targetAmount
* @property string $code
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi2 extends \common\models\lower_management\master\Kgi2Master{
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
