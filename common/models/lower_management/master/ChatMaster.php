<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "chat".
*
    * @property integer $chatId
    * @property integer $jobId
    * @property integer $employeeId
    * @property string $message
    * @property integer $parentId
    * @property integer $status
    * @property string $createDateTime
*/
class ChatMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'chat';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'employeeId'], 'required'],
            [['jobId', 'employeeId', 'parentId'], 'integer'],
            [['message'], 'string'],
            [['createDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'chatId' => 'Chat ID',
    'jobId' => 'Job ID',
    'employeeId' => 'Employee ID',
    'message' => 'Message',
    'parentId' => 'Parent ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
];
}
}
