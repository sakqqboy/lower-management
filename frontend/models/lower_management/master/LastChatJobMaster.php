<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "last_chat_job".
*
    * @property integer $id
    * @property integer $chatId
    * @property integer $jobId
    * @property string $createDateTime
*/
class LastChatJobMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'last_chat_job';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['chatId', 'jobId'], 'required'],
            [['chatId', 'jobId'], 'integer'],
            [['createDateTime'], 'safe'],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'chatId' => 'Chat ID',
    'jobId' => 'Job ID',
    'createDateTime' => 'Create Date Time',
];
}
}
