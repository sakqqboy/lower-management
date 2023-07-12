<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_complain".
*
    * @property integer $id
    * @property integer $jobId
    * @property string $complain
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobComplainMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_complain';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'complain'], 'required'],
            [['jobId'], 'integer'],
            [['complain'], 'string'],
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
    'id' => 'ID',
    'jobId' => 'Job ID',
    'complain' => 'Complain',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
