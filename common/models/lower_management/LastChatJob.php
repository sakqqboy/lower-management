<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LastChatJobMaster;

/**
* This is the model class for table "last_chat_job".
*
* @property integer $id
* @property integer $chatId
* @property integer $jobId
* @property string $createDateTime
*/

class LastChatJob extends \common\models\lower_management\master\LastChatJobMaster{
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
