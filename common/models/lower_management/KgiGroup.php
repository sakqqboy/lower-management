<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\KgiGroupMaster;

/**
* This is the model class for table "kgi_group".
*
* @property integer $kgiGroupId
* @property string $kgiGroupName
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class KgiGroup extends \common\models\lower_management\master\KgiGroupMaster{
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
