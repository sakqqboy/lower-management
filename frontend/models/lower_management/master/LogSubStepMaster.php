<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_sub_step".
*
    * @property integer $id
    * @property integer $additionalStepId
    * @property string $oldDueDate
    * @property string $newDueDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogSubStepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_sub_step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['additionalStepId', 'oldDueDate', 'newDueDate'], 'required'],
            [['additionalStepId'], 'integer'],
            [['oldDueDate', 'newDueDate', 'createDateTime', 'updateDateTime'], 'safe'],
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
    'additionalStepId' => 'Additional Step ID',
    'oldDueDate' => 'Old Due Date',
    'newDueDate' => 'New Due Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
