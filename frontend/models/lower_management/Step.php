<?php

namespace frontend\models\lower_management;

use common\models\ModelMaster;
use Yii;
use \frontend\models\lower_management\master\StepMaster;

/**
 * This is the model class for table "step".
 *
 * @property integer $stepId
 * @property string $stepName
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Step extends \frontend\models\lower_management\master\StepMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 99;
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
    public static function stepName($stepId)
    {
        $step = Step::find()->select('stepName')->where(["stepId" => $stepId])->asArray()->one();
        if (isset($step)) {
            return $step["stepName"];
        } else {
            return 'step not set';
        }
    }
    public static function sort($stepId)
    {
        $step = Step::find()->select('sort')->where(["stepId" => $stepId])->asArray()->one();
        if (isset($step)) {
            return $step["sort"];
        } else {
            return ' ';
        }
    }
    public static function stepNameByJsId($jobStepId)
    {
        $text = '';
        $js = JobStep::find()->select('stepId,dueDate')->where(["jobStepId" => $jobStepId])->asArray()->one();
        $step = Step::find()->select('stepName,sort')->where(["stepId" => $js["stepId"]])->asArray()->one();
        if (isset($step) && !empty($step)) {
            $text .= "<div class='row'><div class='col-12 text-left'>" . $step["sort"] . '. ' . $step["stepName"] . "</div>";
            if ($js["dueDate"] != '') {
                $text .= "<div class='col-12 text-right'>" . ModelMaster::engDate($js["dueDate"], 2) . "</div></div>";
            }
            return $text;
        } else {
            return 'step not set';
        }
    }
    public static function checkNewStep($stepName, $jobTypeId)
    {
        $step = Step::find()->select('stepId')
            ->where(["stepName" => $stepName, "jobTypeId" => $jobTypeId])
            ->asArray()
            ->one();
        if (isset($step) && !empty($step)) {
            return 1;
        } else {
            return 0;
        }
    }
}
