<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ChatMaster;

/**
* This is the model class for table "chat".
*
* @property integer $chatId
* @property integer $jobId
* @property integer $employeeId
* @property string $messege
* @property integer $status
* @property string $createDateTime
*/

class Chat extends \common\models\lower_management\master\ChatMaster{
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
