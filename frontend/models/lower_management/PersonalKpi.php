<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\PersonalKpiMaster;
use yii\db\Expression;

/**
 * This is the model class for table "personal_kpi".
 *
 * @property integer $personalKpiId
 * @property integer $kpiId
 * @property string $targetAmount
 * @property string $personalTargetAmount
 * @property integer $employeeId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class PersonalKpi extends \frontend\models\lower_management\master\PersonalKpiMaster
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
    public static function setPernalkpi($kpiId, $employeeId)
    {
        $kpi = Kpi::find()->where(["kpiId" => $kpiId])->one();
        $personalKpi = PersonalKpi::find()->where(["kpiId" => $kpiId, "employeeId" => $employeeId])->one();
        if (isset($personalKpi) && !empty($personalKpi)) {
            $personalKpi->targetAmount = $kpi["targetAmount"];
            $personalKpi->save(false);
        } else {
            $personalKpi = new PersonalKpi();
            $personalKpi->kpiId = $kpiId;
            $personalKpi->targetAmount = $kpi->targetAmount;
            $personalKpi->personalTargetAmount = $kpi->targetAmount;
            $personalKpi->employeeId = $employeeId;
            $personalKpi->status = 1;
            $personalKpi->createDateTime = new Expression('NOW()');
            $personalKpi->updateDateTime = new Expression('NOW()');
            $personalKpi->save(false);
        }
    }
    public static function updateProgress($year, $month, $day, $pkpiId)
    {
        $kpiDetail = PersonalKpiDetail::find()
            ->select('personal_kpi_detail.amount,personal_kpi_detail.personalKpiDetailId,kpi.amountType')
            ->JOIN("LEFT JOIN", "personal_kpi pk", "pk.personalKpiId=personal_kpi_detail.pkpiId")
            ->JOIN("LEFT JOIN", "kpi", "kpi.kpiId=pk.kpiId")
            ->where([
                "personal_kpi_detail.year" => $year,
                "personal_kpi_detail.month" => $month,
                "personal_kpi_detail.day" => $day,
                "pk.employeeId" => Yii::$app->user->id,
                "pk.personalKpiId" => $pkpiId
            ])
            ->asArray()
            ->orderBy('personal_kpi_detail.personalKpiDetailId')
            ->all();
        $data = [];
        if (isset($kpiDetail) && count($kpiDetail) > 0) {
            foreach ($kpiDetail as $detail) :
                $data[$detail["personalKpiDetailId"]] = [
                    "amount" => number_format($detail["amount"], 2),
                    "amountType" => $detail["amountType"] == 1 ? '' : '%'
                ];
            endforeach;
            //throw new Exception(print_r($kpiDetail, true));
        }
        return $data;
    }
}
