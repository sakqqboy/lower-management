<?php

namespace frontend\modules\mms\controllers;

use common\models\ModelMaster;
use DivisionByZeroError;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Chart;
use frontend\models\lower_management\ChartData;
use frontend\models\lower_management\ChartResult;
use frontend\models\lower_management\Country;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\PieTitle;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use Yii;
use yii\base\ErrorException;
use yii\db\Expression;
use yii\web\Controller;

/**
 * Default controller for the `mms` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $right = 'all';
        $access = Type::checkType($right);
        $colors = Chart::setColor();
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $branch = Branch::find()->select('branchId,branchName')
            ->where(["status" => Branch::STATUS_ACTIVE])
            ->asArray()
            ->all();
        $team = Team::find()->select('teamId,teamName')
            ->where(["status" => Team::STATUS_ACTIVE])
            ->asArray()
            ->all();
        $chart = Chart::find()->where(["status" => 1])->orderBy('createDateTime DESC')->asArray()->all();

        return $this->render('index', [
            "branch" => $branch,
            "team" => $team,
            "charts" => $chart,
            "colors" => $colors
        ]);
    }
    public function actionAddingReport()
    {
        $country = Country::find()->select('countryId,countryName')->where(["status" => 1, "hasBranch" => 1])->asArray()->all();
        return $this->render('formular_form', [
            "country" => $country
        ]);
    }
    public function actionSaveChart()
    {
        if (isset($_POST["title"])) {
            $chart = new Chart();
            $chart->chartName = $_POST["title"];
            $chart->chartType = $_POST["graphType"];
            $chart->yName = $_POST["yName"];
            $chart->startYear = $_POST["startYear"];
            $chart->xType = $_POST["term"];
            $chart->countryId = $_POST["country"];
            $chart->dataType = $_POST["dataType"];
            $chart->yUnit = $_POST["yUnit"];
            $chart->formula = isset($_POST["formula"]) ? $_POST["formula"] : null;
            $chart->status = 1;
            $chart->resultName = $_POST["resultName"];
            $chart->createDateTime = new Expression('NOW()');
            $chart->updateDateTime = new Expression('NOW()');
            if ($chart->save(false)) {
                $chartId = Yii::$app->db->lastInsertID;
                if ($_POST["graphType"] == Chart::TYPE_PIE) {
                    $totalPiece = $_POST["totalPiece"];
                    if (isset($_POST["piece"])) {
                        $i = 0;
                        while ($i < $totalPiece) {
                            $result = new ChartResult();
                            $result->index = $i;
                            $result->chartId = $chartId;
                            $result->value = $_POST["value"][$i];
                            $result->save(false);
                            $title = new PieTitle();
                            $title->chartId = $chartId;
                            $title->index = $i;
                            $title->title = $_POST["piece"][$i];
                            $title->save(false);
                            $i++;
                        }
                    }
                } else {
                    if (isset($_POST["dataRow"])) {
                        foreach ($_POST["dataRow"] as $row => $dataColumn) :
                            foreach ($dataColumn as $column => $dataInput) :
                                if ($dataInput != null) {
                                    $data = new ChartData();
                                    $data->row = $row;
                                    $data->rowName = $_POST["row"][$row];
                                    $data->index = $column;
                                    $data->value = $dataInput;
                                    $data->chartId = $chartId;
                                    $data->save(false);
                                }
                            endforeach;
                        endforeach;
                        if (isset($_POST["result"]) && count($_POST["result"]) > 0) {
                            foreach ($_POST["result"] as $index => $resultCal) :
                                $result = new ChartResult();
                                $result->index = $index;
                                $result->chartId = $chartId;
                                $result->value = $resultCal;
                                $result->save(false);
                            endforeach;
                        }
                    }
                }
                return $this->redirect('index');
            }
        }
    }
    public function actionShowGraph($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $graphId =  $param["chartId"];
        $colors = Chart::setColor();
        $chart = Chart::find()->where(["chartId" => $graphId])->asArray()->one();
        $chartData = ChartData::find()->where(["chartId" => $graphId])->asArray()->all();
        $chartResult = ChartResult::find()->where(["chartId" => $graphId])->asArray()->orderBy('index')->all();
        $xData = Chart::getXvacter($chart["xType"], $chart["startYear"]);
        $dataType = "<b>{point.name}</b>: {point.y}";
        if ($chart["dataType"] == 2) {
            $dataType = "<b>{point.name}</b>: {point.percentage:.2f} %";
        }
        if ($chart["chartType"] == Chart::TYPE_LINE) {
            $data = Chart::chartResult($graphId);
            if (isset($data["data"]) && count($data["data"]) > 0) {
                foreach ($data as $all) :
                    foreach ($all as $row => $dataArr) :
                        $i = 0;
                        while ($i < count($xData)) {
                            if (!isset($dataArr[$i])) {
                                $dataArr[$i] = null;
                            }
                            $i++;
                        }
                        ksort($dataArr);
                        $value[$row - 1] = [
                            "name" => Chart::findRowName($graphId, $row),
                            "data" => $dataArr
                        ];
                    endforeach;
                endforeach;
            } else {
                $value[0] = [
                    "name" => $chart["resultName"],
                    "data" => $data
                ];
            }
            return $this->render('line', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult
            ]);
        }
        if ($chart["chartType"] == Chart::TYPE_PIE) {
            $result = Chart::chartResult($graphId);
            if (isset($result) && count($result) > 0) {
                foreach ($result as $index => $re) :
                    $value[$index] = [
                        Chart::pieVacter($chart["chartId"], $index), $re
                    ];
                endforeach;
            }
            //throw new exception(print_r($value, true));
            return $this->render('pie', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult,
                "colors" => $colors,
                "dataType" =>  $dataType
            ]);
        }
        if ($chart["chartType"] == Chart::TYPE_BAR) {
            $data = Chart::chartResult($graphId);
            if (isset($data["data"]) && count($data["data"]) > 0) {
                foreach ($data as $all) :
                    foreach ($all as $row => $dataArr) :
                        $i = 0;
                        while ($i < count($xData)) {
                            if (!isset($dataArr[$i])) {
                                $dataArr[$i] = 0 * 1;
                            }
                            $i++;
                        }
                        ksort($dataArr);
                        $value[$row - 1] = [
                            "name" => Chart::findRowName($graphId, $row),
                            "data" => $dataArr
                        ];
                    endforeach;
                endforeach;
            } else {
                $value[0] = [
                    "name" => 'Result',
                    "data" => $data
                    // "data" => $data
                ];
            }
            //throw new exception(print_r($value, true));
            return $this->render('bar', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult
            ]);
        }
    }
    public function actionFormular()
    {
        $text = "";
        $res = [];
        $format = [];
        if ($_POST["type"] == 1) {
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
        if ($_POST["type"] == 2) { //period 5 day
            $format[0] = "1-5";
            $format[1] = "6-10";
            $format[2] = "11-15";
            $format[3] = "16-20";
            $format[4] = "21-25";
            $format[5] = "25-31";
        }
        if ($_POST["type"] == 3) { //12 month
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
        if ($_POST["type"] == 4) {
            $startYear = $_POST["startYear"];
            $i = 0;
            while ($i < 10) {
                $format[$i] = ($startYear * 1) + $i;
                $i++;
            }
        }
        $text = $this->renderAjax('formular', ["format" => $format, "type" => $_POST["type"]]);
        $res["status"] = true;
        $res["text"] = $text;
        return json_encode($res);
    }
    public function getXvacter($typeId)
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
        return $format;
    }
    public function actionIndex2()
    {
        $thisYear = date('Y');
        $lastYear = $thisYear - 1;

        $month[$lastYear] = ModelMaster::month();
        $month[$thisYear] = ModelMaster::month();
        $jobData = [];
        $total = [];
        $textData = [];
        $chartData = [];
        $monthText = [];
        $chartPie = [];
        $jobs = JobCategory::find()
            ->select('job_category.targetDate as targetDate,j.fee,j.teamId,MONTH(job_category.targetDate) as month ,YEAR(job_category.targetDate) as year')
            ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
            ->JOIN("LEFT JOIN", "team t", "t.teamId=j.teamId")
            ->where("j.status in (" . Job::STATUS_INPROCESS . "," . Job::STATUS_COMPLETE . ")")
            ->andWhere("job_category.status in (" . JobCategory::STATUS_INPROCESS . "," . JobCategory::STATUS_COMPLETE . ")")
            ->andWhere("YEAR(job_category.targetDate) in ($thisYear,$lastYear)")
            ->orderBy("t.teamName,year,month")
            ->asArray()
            ->all();
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                if (isset($jobData[$job["teamId"]][$job["year"]][$job["month"]])) {
                    $jobData[$job["teamId"]][$job["year"]][$job["month"]] += $job["fee"];
                } else {
                    $jobData[$job["teamId"]][$job["year"]][$job["month"]] = $job["fee"];
                }

                if (isset($total[$job["year"]][$job["month"]])) {
                    $total[$job["year"]][$job["month"]] += $job["fee"];
                } else {
                    $total[$job["year"]][$job["month"]] = $job["fee"];
                }
            endforeach;
        }
        if (count($jobData) > 0) {
            foreach ($jobData as $teamId => $jobYear) :
                $textData[$teamId] = [];
                $i = 0;
                foreach ($month as $year => $monthArr) :
                    foreach ($monthArr as $monthValue => $monthT) :

                        if (isset($jobData[$teamId][$year][$monthValue])) {
                            $textData[$teamId][$i] = $jobData[$teamId][$year][$monthValue] * 1;
                        } else {
                            $textData[$teamId][$i] = 0 * 1;
                        }
                        $monthText[$i] = $monthT . '-' . substr($year, -2);
                        $i++;
                    endforeach;
                endforeach;
            endforeach;
        }
        if (count($textData) > 0) {
            $i = 0;
            foreach ($textData as $teamId => $d) :
                $teamName = Team::teamName($teamId);
                $chartData[$i] = [
                    'name' =>  $teamName,
                    'data' => $d
                ];
                $chartPie[$i] = [
                    'type' => 'pie',
                    'name' =>  $teamName,
                    'data' => $d
                ];
                $i++;
            endforeach;
        }
        return $this->render('index2', [
            "month" => $month,
            "thisYear" => $thisYear,
            "lastYear" => $lastYear,
            "jobData" => $jobData,
            "total" => $total,
            "chartData" => $chartData,
            "monthTextChart" => $monthText,
            "chartPie" => $chartPie
        ]);
    }
    public function actionRenderPie()
    {
        $text = '';
        $value = [];
        $res = [];
        $res["status"] = false;
        $number = $_POST["number"];
        if ($number != 0) {
            $i = 0;
            while ($i < $number) {
                $value[$i] = 0;
                $i++;
            }
            $dataType = "<b>{point.name}</b>: {point.y}";
            if ($_POST["dataTypeInput"] == 2) {
                $dataType = "<b>{point.name}</b>: {point.percentage:.2f} %";
            }
            if (count($value) > 0) {

                $text = $this->renderAjax('pie_chart', [
                    "value" => $value,
                    "chartName" => $_POST["chartName"],
                    "dataType" => $dataType

                ]);
                $res["status"] = true;
                $res["text"] = $text;
            }
        }
        return json_encode($res);
    }
    public function actionGeneratePieChart()
    {
        $totalPiece = $_POST["totalPiece"];
        $colors = Chart::setColor();
        $value = [];
        $title = [];
        if (trim($totalPiece) != '' && $totalPiece != 0) {
            $i = 0;
            while ($i < $totalPiece) {
                $title[$i] = $_POST["title"][$i] == null ? 'not set' : $_POST["title"][$i];
                $value[$i] = [$title[$i], $_POST["pieceValue"][$i] == null ? 0 : $_POST["pieceValue"][$i] * 1];


                $i++;
            }
            //  throw new Exception(print_r($title, true));
            $dataType = "<b>{point.name}</b>: {point.y}";
            if ($_POST["dataTypeInput"] == 2) {
                $dataType = "<b>{point.name}</b>: {point.percentage:.2f} %";
            }
            $text = $this->renderAjax('mockup-pie', [
                "title" => $title,
                "value" => $value,
                "colors" => $colors,
                "chartName" => $_POST["chartName"],
                "dataType" =>  $dataType
            ]);
            $res["text"] = $text;
            $res["status"] = true;
        } else {
            $res["status"] = false;
        }
        return json_encode($res);
    }
    public function actionCalculateResult()
    {
        $totalColumn = $_POST["totalColumn"];
        $allData = [];
        $result = [];
        $res = [];
        if (isset($_POST["dataInput"]) && count($_POST["dataInput"]) > 0 && trim($_POST["formula"]) != "") {
            $row = 0;
            $eachRow = 0;
            foreach ($_POST["dataInput"] as $column => $data) :
                $allData[$row][$eachRow] = $data;
                if ($eachRow == ($totalColumn - 1)) {
                    $eachRow = 0;
                    $row++;
                } else {
                    $eachRow++;
                }
            endforeach;
        }
        if (count($allData) > 0) {
            $formula = str_replace('r1', '$allData[0][$i]', $_POST["formula"]);
            $j = 2;
            while ($j <= $_POST["totalRow"]) {
                $formula = str_replace('r' . $j, '$allData[' . ($j - 1) . '][$i]',  $formula);
                $j++;
            }
            $i = 0;

            while ($i < $totalColumn) {
                $j = 0;
                while ($j <= $_POST["totalRow"]) {

                    if (isset($allData[$j][$i]) && $allData[$j][$i] == "") {
                        $allData[$j][$i] = 0;
                    }
                    $j++;
                }
                try {
                    $result[$i] = eval('return ' . $formula . ';');
                } catch (ErrorException $e) {
                    $result[$i] = "x/0";
                }
                $i++;
            }
        }
        if (count($result) > 0) {
            $res["status"] = true;
            $res["result"] = $result;
        } else {
            $res["status"] = false;
        }
        return json_encode($res);
    }
    public function actionEditGraph($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $graphId = $param["chartId"];
        $colors = Chart::setColor();
        $chart = Chart::find()->where(["chartId" => $graphId])->asArray()->one();
        $chartData = ChartData::find()->where(["chartId" => $graphId])->asArray()->all();
        $chartResult = ChartResult::find()->where(["chartId" => $graphId])->asArray()->orderBy('index')->all();
        $country = Country::find()->select('countryId,countryName')->where(["status" => 1, "hasBranch" => 1])->asArray()->all();
        $xData = Chart::getXvacter($chart["xType"], $chart["startYear"]);
        $dataType = "<b>{point.name}</b>: {point.y}";
        if ($chart["dataType"] == 2) {
            $dataType = "<b>{point.name}</b>: {point.percentage:.2f} %";
        }
        if ($chart["chartType"] == Chart::TYPE_LINE) {
            $data = Chart::chartResult($graphId);
            if (isset($data["data"]) && count($data["data"]) > 0) {
                foreach ($data as $all) :
                    foreach ($all as $row => $dataArr) :
                        $i = 0;
                        while ($i < count($xData)) {
                            if (!isset($dataArr[$i])) {
                                $dataArr[$i] = null;
                            }
                            $i++;
                        }
                        ksort($dataArr);
                        $value[$row - 1] = [
                            "name" => Chart::findRowName($graphId, $row),
                            "data" => $dataArr
                        ];
                    endforeach;
                endforeach;
            } else {
                $value[0] = [
                    "name" => $chart["resultName"],
                    "data" => $data
                ];
            }
            return $this->render('edit_line', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult,
                "country" => $country
            ]);
        }
        if ($chart["chartType"] == Chart::TYPE_PIE) {
            $result = Chart::chartResult($graphId);
            if (isset($result) && count($result) > 0) {
                foreach ($result as $index => $re) :
                    $value[$index] = [
                        Chart::pieVacter($chart["chartId"], $index), $re
                    ];
                endforeach;
            }
            return $this->render('edit_pie', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult,
                "colors" => $colors,
                "dataType" =>  $dataType,
                "country" => $country
            ]);
        }
        if ($chart["chartType"] == Chart::TYPE_BAR) {
            $data = Chart::chartResult($graphId);
            if (isset($data["data"]) && count($data["data"]) > 0) {
                foreach ($data as $all) :
                    foreach ($all as $row => $dataArr) :
                        $i = 0;
                        while ($i < count($xData)) {
                            if (!isset($dataArr[$i])) {
                                $dataArr[$i] = 0 * 1;
                            }
                            $i++;
                        }
                        ksort($dataArr);
                        $value[$row - 1] = [
                            "name" => Chart::findRowName($graphId, $row),
                            "data" => $dataArr
                        ];
                    endforeach;
                endforeach;
            } else {
                $value[0] = [
                    "name" => 'Result',
                    "data" => $data
                    // "data" => $data
                ];
            }
            return $this->render('edit_bar', [
                "chart" => $chart,
                "xData" => $xData,
                "chartData" => $chartData,
                "value" => $value,
                "chartResult" => $chartResult,
                "country" => $country
            ]);
        }
    }
}
