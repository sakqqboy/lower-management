<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_fee".
*
    * @property integer $id
    * @property string $jobId
    * @property integer $jobCategoryId
    * @property integer $jobStepId
    * @property string $fee
    * @property string $feeChargeDate
    * @property string $advanceReceivable
    * @property string $advancedChargeDate
    * @property string $outSourcingFee
    * @property integer $estimateTime
    * @property string $createDateTime
*/
class LogFeeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_fee';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId'], 'required'],
            [['jobCategoryId', 'jobStepId', 'estimateTime'], 'integer'],
            [['fee', 'advanceReceivable', 'outSourcingFee'], 'number'],
            [['feeChargeDate', 'advancedChargeDate', 'createDateTime'], 'safe'],
            [['jobId'], 'string', 'max' => 45],
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
    'jobCategoryId' => 'Job Category ID',
    'jobStepId' => 'Job Step ID',
    'fee' => 'Fee',
    'feeChargeDate' => 'Fee Charge Date',
    'advanceReceivable' => 'Advance Receivable',
    'advancedChargeDate' => 'Advanced Charge Date',
    'outSourcingFee' => 'Out Sourcing Fee',
    'estimateTime' => 'Estimate Time',
    'createDateTime' => 'Create Date Time',
];
}
}
