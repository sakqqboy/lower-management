<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobResponsibilityMaster;
use yii\db\Expression;

/**
 * This is the model class for table "job_responsibility".
 *
 * @property integer $id
 * @property integer $jobId
 * @property integer $employeeId
 * @property integer $responsibility
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class JobResponsibility extends \frontend\models\lower_management\master\JobResponsibilityMaster
{
    /**
     * @inheritdoc
     */
    const PIC1 = 2;
    const PIC2 = 3;
    const APPROVER = 4;
    const CREATER = 5;
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
    public static function jobResponseText($jobId, $typeId)
    {
        $text = '';
        //$type = Type::find()->select('typeId')->where(["typeName" => $typeName])->asArray()->one();
        $jobResponsibility = JobResponsibility::find()
            ->select('em.employeeNickName')
            ->JOIN("LEFT JOIN", "employee em", "em.employeeId=job_responsibility.employeeId")
            ->where(["job_responsibility.jobId" => $jobId, "job_responsibility.responsibility" => $typeId])
            ->asArray()
            ->all();
        if (isset($jobResponsibility) && count($jobResponsibility) > 0) {
            foreach ($jobResponsibility as $jRes) :
                $text .= " " . $jRes["employeeNickName"] . ",";
            endforeach;
        } else {
            $text = "-";
        }
        if ($text != "") {
            $text = substr($text, 0, -1);
        }
        return $text;
    }
    public static function jobResponseTextDetail($jobId, $typeId)
    {
        $text = '';
        //$type = Type::find()->select('typeId')->where(["typeName" => $typeName])->asArray()->one();
        $jobResponsibility = JobResponsibility::find()
            ->select('em.employeeNickName,job_responsibility.percentage')
            ->JOIN("LEFT JOIN", "employee em", "em.employeeId=job_responsibility.employeeId")
            ->where(["job_responsibility.jobId" => $jobId, "job_responsibility.responsibility" => $typeId])
            ->asArray()
            ->all();
        if (isset($jobResponsibility) && count($jobResponsibility) > 0) {
            foreach ($jobResponsibility as $jRes) :
                $text .= " " . $jRes["employeeNickName"] . "&nbsp;&nbsp;&nbsp;" . $jRes["percentage"] . "%,";
            endforeach;
        } else {
            $text = "-";
        }
        if ($text != "") {
            $text = substr($text, 0, -1);
        }
        return $text;
    }
    public static function saveLogApprover($jobId, $approverId, $jobCategoryId, $jobStepId)
    {
        $log = new LogJobApprover();
        $log->jobId = $jobId;
        $log->employeeId = $approverId;
        $log->jobCategoryId = $jobCategoryId;
        $log->jobStepId = $jobStepId;
        $log->createDateTime = new Expression('NOW()');
        $log->updateDateTime = new Expression('NOW()');
        $log->save(false);
    }
}
