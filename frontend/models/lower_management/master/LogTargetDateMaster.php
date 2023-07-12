<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_target_date".
*
    * @property integer $id
    * @property integer $jobCategoryId
    * @property string $oldTargetDate
    * @property string $newTargetDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogTargetDateMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_target_date';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobCategoryId', 'newTargetDate'], 'required'],
            [['jobCategoryId'], 'integer'],
            [['oldTargetDate', 'newTargetDate', 'createDateTime', 'updateDateTime'], 'safe'],
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
    'jobCategoryId' => 'Job Category ID',
    'oldTargetDate' => 'Old Target Date',
    'newTargetDate' => 'New Target Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
