<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\ChartMaster;

/**
 * This is the model class for table "chart".
 *
 * @property integer $chartId
 * @property string $chartName
 * @property integer $chartType
 * @property string $yName
 * @property integer $xType
 * @property integer $dataType
 * @property string $formula
 * @property string $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Chart extends \frontend\models\lower_management\master\ChartMaster
{
    /**
     * @inheritdoc
     */
    const TYPE_LINE = 1;
    const TYPE_PIE = 2;
    const TYPE_BAR = 3;
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
    public static function chartResult($chartId)
    {
        $res = [];
        $chart = Chart::find()->where(["chartId" => $chartId])->asArray()->one();
        $result = ChartResult::find()->where(["chartId" => $chartId])->asArray()->all();
        if (isset($result) && count($result) > 0) {
            foreach ($result as $r) :
                $res[$r["index"]] = ($r["value"] * 1);
            endforeach;
        } else {
            $data = ChartData::find()->where(["chartId" => $chartId])->asArray()->all();
            if (isset($data) && count($data) > 0) {
                foreach ($data as $r) :
                    $res["data"][$r["row"]][$r["index"]] = ($r["value"] * 1);
                endforeach;
            }
        }
        return $res;
    }
    public static function pieVacter($chartId, $index)
    {
        $title = PieTitle::find()->where(["chartId" => $chartId, "index" => $index])->asArray()->one();
        return isset($title) ? $title["title"] : '';
    }
    public static function getXvacter($typeId, $startYear)
    {
        $format = [];
        if ($typeId == 1) {
            $format[0] = "1";
            $format[1] = "2";
            $format[2] = "3";
            $format[3] = "4";
            $format[4] = "5";
            $format[5] = "6";
            $format[6] = "7";
            $format[7] = "8";
            $format[8] = "9";
            $format[9] = "10";
            $format[10] = "11";
            $format[11] = "12";
            $format[12] = "13";
            $format[13] = "14";
            $format[14] = "15";
            $format[15] = "16";
            $format[16] = "17";
            $format[17] = "18";
            $format[18] = "19";
            $format[19] = "20";
            $format[20] = "21";
            $format[21] = "22";
            $format[22] = "23";
            $format[23] = "24";
            $format[24] = "25";
            $format[25] = "26";
            $format[26] = "27";
            $format[27] = "28";
            $format[28] = "29";
            $format[29] = "30";
            $format[30] = "31";
        }
        if ($typeId == 2) { //period 5 day
            $format[0] = "1-5";
            $format[1] = "6-10";
            $format[2] = "11-15";
            $format[3] = "16-20";
            $format[4] = "21-25";
            $format[5] = "25-31";
        }
        if ($typeId == 3) { //12 month
            $format[0] = "Jan";
            $format[1] = "Feb";
            $format[2] = "Mar";
            $format[3] = "Apr";
            $format[4] = "May";
            $format[5] = "Jun";
            $format[6] = "Jul";
            $format[7] = "Aug";
            $format[8] = "Sep";
            $format[9] = "Oct";
            $format[10] = "Nov";
            $format[11] = "Dec";
        }
        if ($typeId == 4 && $startYear != null) {
            $i = 0;
            while ($i < 10) {
                $format[$i] = $i + $startYear;
                $i++;
            }
        }
        return $format;
    }
    public static function findRowName($chartId, $row)
    {
        $chart = ChartData::find()->where(["chartId" => $chartId, "row" => $row])->asArray()->one();
        if (isset($chart) && !empty($chart)) {
            return $chart["rowName"];
        } else {
            return '';
        }
    }
    public static function setColor()
    {
        $colors = [
            "#FF99CC", "#FFCC66", "#99CCFF",
            "#FF6666", "#6A5ACD", "#00FFCC",
            "#336600", "#000000", "#339999",
            "#4682B4", "#8B4513", "#BC8F8F",
            "#9400D3", "#D8BFD8", "#D2691E",
            "#6B8E23", "#FF69B4", "#A020F0",
            "#B03060", "#D2B48C"
        ];
        return $colors;
    }
}
