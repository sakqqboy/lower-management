<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "read_chat".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $employeeId
    * @property string $createDateTime
*/
class ReadChatMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'read_chat';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'employeeId'], 'required'],
            [['jobId', 'employeeId'], 'integer'],
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
    'jobId' => 'Job ID',
    'employeeId' => 'Employee ID',
    'createDateTime' => 'Create Date Time',
];
}
}
