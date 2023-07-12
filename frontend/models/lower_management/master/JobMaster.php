<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job".
*
    * @property integer $jobId
    * @property string $jobName
    * @property string $jobNumber
    * @property integer $clientId
    * @property integer $branchId
    * @property integer $categoryId
    * @property integer $fieldId
    * @property integer $jobTypeId
    * @property integer $teamId
    * @property string $fee
    * @property string $feeChargeDate
    * @property string $currencyId
    * @property string $advanceReceivable
    * @property string $advancedChargeDate
    * @property string $outsourcingFee
    * @property integer $estimateTime
    * @property string $p1Time
    * @property string $p2Time
    * @property integer $status
    * @property string $startDate
    * @property string $memo
    * @property string $url
    * @property integer $report
    * @property string $checkListPath
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['clientId', 'branchId', 'categoryId', 'fieldId', 'jobTypeId', 'teamId', 'estimateTime'], 'integer'],
            [['branchId', 'categoryId', 'fieldId', 'jobTypeId', 'teamId', 'fee', 'currencyId'], 'required'],
            [['fee', 'advanceReceivable', 'outsourcingFee'], 'number'],
            [['feeChargeDate', 'advancedChargeDate', 'startDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['memo', 'url', 'checkListPath'], 'string'],
            [['jobName'], 'string', 'max' => 255],
            [['jobNumber'], 'string', 'max' => 100],
            [['currencyId', 'p1Time', 'p2Time'], 'string', 'max' => 45],
            [['status', 'report'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobId' => 'Job ID',
    'jobName' => 'Job Name',
    'jobNumber' => 'Job Number',
    'clientId' => 'Client ID',
    'branchId' => 'Branch ID',
    'categoryId' => 'Category ID',
    'fieldId' => 'Field ID',
    'jobTypeId' => 'Job Type ID',
    'teamId' => 'Team ID',
    'fee' => 'Fee',
    'feeChargeDate' => 'Fee Charge Date',
    'currencyId' => 'Currency ID',
    'advanceReceivable' => 'Advance Receivable',
    'advancedChargeDate' => 'Advanced Charge Date',
    'outsourcingFee' => 'Outsourcing Fee',
    'estimateTime' => 'Estimate Time',
    'p1Time' => 'P1time',
    'p2Time' => 'P2time',
    'status' => 'Status',
    'startDate' => 'Start Date',
    'memo' => 'Memo',
    'url' => 'Url',
    'report' => 'Report',
    'checkListPath' => 'Check List Path',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
