<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "keep_noti".
*
    * @property integer $keepNotiId
    * @property integer $jobId
    * @property integer $employeeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KeepNotiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'keep_noti';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'employeeId'], 'required'],
            [['jobId', 'employeeId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'keepNotiId' => 'Keep Noti ID',
    'jobId' => 'Job ID',
    'employeeId' => 'Employee ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
