<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\FiscalYearMaster;

/**
 * This is the model class for table "fiscal_year".
 *
 * @property integer $fiscalYearId
 * @property integer $fiscalYear
 * @property string $createDateTime
 * @property string $closeDateTime
 * @property integer $status
 * @property string $updateDateTime
 */

class FiscalYear extends \frontend\models\lower_management\master\FiscalYearMaster
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
    public static function currentFiscalYear()
    {

        $fiscalYear = FiscalYear::find()->where(["status" => 1])->asArray()->orderBy('fiscalYear DESC')->one();
        if (isset($fiscalYear) && !empty($fiscalYear)) {
            $year = $fiscalYear["fiscalYear"];
        } else {
            $year = date('Y');
        }
        return $year;
    }
    public static function allFiscalYear()
    {
        $currentYear = date('Y');
        $fiscalYear = JobCategory::find()
            ->select('fiscalYear')
            ->where(["status" => [1, 4]])
            ->andWhere("fiscalYear>=2000 and fiscalYear<=$currentYear")
            ->groupBy('fiscalYear')
            ->asArray()
            ->orderBy('fiscalYear DESC')
            ->all();
        return $fiscalYear;
    }
}
