<?php

namespace frontend\models\lower_management;

use common\models\ModelMaster;
use Yii;
use \frontend\models\lower_management\master\SubmitReportMaster;
use yii\base\Exception;

/**
 * This is the model class for table "submit_report".
 *
 * @property integer $submitReportId
 * @property integer $jobId
 * @property integer $CategoryId
 * @property integer $stepId
 * @property string $submitDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class SubmitReport extends \frontend\models\lower_management\master\SubmitReportMaster
{
    /**
     * @inheritdoc
     */
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
    public static function isSubmitReport($jobId)
    {
        date_default_timezone_set("Asia/Bangkok");
        $date = [];
        $today = date('l');
        $month = date('m');
        $year = date('y');
        $startDay = "Monday";
        $endDay = "Sunday";
        //$yesterday =  mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
        $i = 0;
        $a = 0;
        $b = 1;
        if ($today != $startDay) { //startday to current day
            while ($i < 10) {
                $time = mktime(0, 0, 0, date("m"), date("d") - $i, date("Y"));
                $date[$a] = date('Y-m-d', $time) . ' 00:00:00';
                $thisDay = date("l", mktime(0, 0, 0, (int)$month, (int)date("d") - $i, $year));
                $a++;
                $i++;
                if ($thisDay == $startDay) {
                    break;
                }
            }
            while ($b < 10) {
                $time = mktime(0, 0, 0, date("m"), date("d") + $b, date("Y"));
                $date[$a] = date('Y-m-d', $time) . ' 00:00:00';
                $thisDay = date("l", mktime(0, 0, 0, (int)$month, (int)date("d") + $b, $year));

                if ($thisDay == $endDay) {
                    break;
                }
                $a++;
                $b++;
            }
        } else {
            $time = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $date[0] = date('Y-m-d', $time) . ' 00:00:00';
        }
        $submit = 0;
        if (count($date) > 0) {
            $submitReport = SubmitReport::find()->select('submitReportId')->where(["jobId" => $jobId, 'submitDate' => $date])->one();
            if (isset($submitReport) && !empty($submitReport)) {
                $submit = 1;
            }
        }
        //throw new Exception(print_r($date, true));
        //$startDate = date("l", mktime(0, 0, 0, (int)$month, (int)'01', $year));
        return $submit;
        // throw new Exception(print_r($date, true));
    }
    public static function subDate($jobId)
    {
        $submitDate = SubmitReport::find()->select('submitDate')->where(["jobId" => $jobId])->orderBy('submitDate DESC')->one();
        if (isset($submitDate) && !empty($submitDate)) {
            $date = ModelMaster::engDate($submitDate["submitDate"]);
        } else {
            $date = '';
        }
        return $date;
    }
    public static function showText($jobId)
    {
        $isSend = self::isSubmitReport($jobId);
        $text = '';
        if ($isSend == 1) {
            $text = "<div class='col-12 text-success text-right font-size18'><i class='fa fa-file' aria-hidden='true'></i></div>";
        } else {
            $text = "<div class='col-12 text-danger text-right font-size18'><i class='fa fa-file' aria-hidden='true'></i></div>";
        }
        return $text;
    }
    public static function totalNeed($branchId)
    {
        $job = Job::find()->where(["branchId" => $branchId, "status" => Job::STATUS_INPROCESS, "report" => 1])->all();
        return count($job);
    }
    public static function countSubmit($report)
    {
        $has = 0;
        if (count($report) > 0) {
            foreach ($report as $job) :
                $isSend = self::isSubmitReport($job["jobId"]);
                if ($isSend == 1) {
                    $has++;
                }
            endforeach;
            return $has;
        } else {
            return 0;
        }
    }
    public static function countNotSubmit($report)
    {
        $notHas = 0;
        if (count($report) > 0) {
            foreach ($report as $job) :
                $isSend = self::isSubmitReport($job["jobId"]);
                if ($isSend == 0) {
                    $notHas++;
                }
            endforeach;
            return $notHas;
        } else {
            return 0;
        }
    }
}
