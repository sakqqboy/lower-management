<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\KpiMaster;
use frontend\modules\kpi\kpi as KpiKpi;

/**
 * This is the model class for table "kpi".
 *
 * @property integer $kpiId
 * @property integer $kgiId
 * @property integer $sectionId
 * @property integer $teamPosition
 * @property string $kpiName
 * @property string $kpiDetail
 * @property string $targetAmount
 * @property integer $amountType
 * @property string $symbolCheck
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Kpi extends \frontend\models\lower_management\master\KpiMaster
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
    public static function unitName($unitId)
    {
        $unit = KpiUnit::find()->select('name')->where(["kpiUnitId" => $unitId])->asArray()->one();
        return  $unit["name"];
    }
    public static function personalKpi($employeeId)
    {
        $employee = Employee::find()->where(["employeeId" => $employeeId])->asArray()->one();
        $personalKpi = [];
        $kgiId = [];
        $kpiId = [];
        $personalKpiArr = [];
        if ($employee["teamPositionId"] != "") {
            $kgi = Kgi::find()
                ->where(["teamPositionId" => $employee["teamPositionId"], "status" => 1])
                ->asArray()
                ->orderBy('createDateTime DESC')
                ->all();
            if (isset($kgi) && count($kgi) > 0) {
                $i = 0;
                $p = 0;
                foreach ($kgi as $k) :
                    PersonalKgi::setPernalkgi($k["kgiId"], $employeeId);
                    $kpi = Kpi::find()
                        ->where(["kgiId" => $k["kgiId"], "status" => 1])
                        ->asArray()
                        ->orderBy('createDateTime DESC')
                        ->all();
                    if (count($kpi) > 0) {
                        foreach ($kpi as $kp) :
                            PersonalKpi::setPernalkpi($kp["kpiId"], $employeeId);
                            $kpiId[$p] = $kp["kpiId"];
                            $p++;
                        endforeach;
                    }
                    $kgiId[$i] = $k["kgiId"];
                    $i++;
                endforeach;
            }
            $personalKpi = PersonalKpi::find()->where(["kpiId" => $kpiId, "employeeId" => $employeeId])->asArray()->orderBy('kpiId')->all();
            if (isset($personalKpi) && count($personalKpi) > 0) {
                foreach ($personalKpi as $pkpi) :
                    $kp = Kpi::find()->where(["kpiId" => $pkpi["kpiId"]])->asArray()->one();
                    $unit = Kpi::unitName($kp["unit"]);
                    if ($kp["amountType"] == 1) {
                        $percent = "";
                    } else {
                        $percent = "%";
                    }
                    if ($kp["symbolCheck"] == 1) {
                        $symbol = ">";
                    }
                    if ($kp["symbolCheck"] == 2) {
                        $symbol = "<";
                    }
                    if ($kp["symbolCheck"] == 3) {
                        $symbol = "=";
                    }

                    $personalKpiArr[$pkpi["personalKpiId"]] = [
                        "kpiName" => $kp["kpiName"],
                        "detail" => $kp["kpiDetail"],
                        "unit" => $unit,
                        "target" => $symbol . " " . number_format($pkpi["personalTargetAmount"], 2) . " " . $percent,
                        "personalAmount" => $pkpi["personalTargetAmount"],
                        "targetAmount" => $symbol . " " . number_format($pkpi["targetAmount"], 2) . " " . $percent,
                        "kpiId" => $pkpi["kpiId"],
                        "achieved" => "0.00%"
                    ];
                endforeach;
            }
        }
        return $personalKpiArr;
    }
}
