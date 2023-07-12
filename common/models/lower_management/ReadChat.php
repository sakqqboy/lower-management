<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ReadChatMaster;

/**
* This is the model class for table "read_chat".
*
* @property integer $id
* @property integer $jobId
* @property integer $employeeId
* @property string $createDateTime
*/

class ReadChat extends \common\models\lower_management\master\ReadChatMaster{
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
