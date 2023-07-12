<?php

namespace frontend\modules\sales\controllers;

use common\carlendar\Carlendar;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Type;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `sales` module
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
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $date = date('Y-m-d');
        $dateValue = Carlendar::currentMonth($date);
        $selectMonth = date('m');
        $selectDate = ModelMaster::engDate($date, 1);
        $currentMonth = "";
        $branch = Branch::find()->select('branchId,branchName')
            ->where(["status" => Branch::STATUS_ACTIVE])
            ->orderBy('branchName')
            ->asArray()
            ->all();
        $year = (int)date('Y');
        $month = (int)date('m');
        return $this->render('index', [
            "dateValue" => $dateValue,
            "selectMonth" => $selectMonth,
            "selectDate" => $selectDate,
            "branch" => $branch,
            "year" => $year,
            "month" => $month,
        ]);
    }
    public function actionSearchJobCarlendar()
    {
        // throw new Exception('11111');
        return $this->redirect(Yii::$app->homeUrl . 'sales/default/show-carlendar/' . ModelMaster::encodeParams([
            "branchId" => $_POST["branchId"],
            "year" => $_POST["year"],
            "month" => $_POST["month"],
            "timezone" => $_POST["timezone"],
            "salesActivity" => $_POST["salesActivity"],
            "existingMeeting" => $_POST["existingMeeting"],
            "internalMeeting" => $_POST["internalMeeting"],
            "other" => $_POST["other"],
        ]));
    }
    public function actionShowCarlendar($hash)
    {
        $params = ModelMaster::decodeParams($hash);
        // throw new Exception(print_r($params, true));
        $year = $params["year"];
        $month = $params["month"];
        $timezone = $params["timezone"];
        $branchId = $params["branchId"];
        $salesActivity = $params["salesActivity"];
        $existingMeeting = $params["existingMeeting"];
        $internalMeetind = $params["internalMeeting"];
        $other = $params["other"];

        $day = date('d');
        if ($month < 10) {
            $month = "0" . $month;
        }
        $date = $year . "-" . $month . "-" . $day;
        $dateValue = Carlendar::currentMonth($date);
        $selectMonth = $month;
        $selectDate = ModelMaster::engDate($date, 1);
        $branch = Branch::find()->select('branchId,branchName')
            ->where(["status" => Branch::STATUS_ACTIVE])
            ->orderBy('branchName')
            ->asArray()
            ->all();
        return $this->render('index', [
            "dateValue" => $dateValue,
            "selectMonth" => $selectMonth,
            "branch" => $branch,
            "branchId" => $branchId,
            "year" => $year,
            "month" => $month,
            "selectDate" => $selectDate,
            "salesActivity" => $salesActivity,
            "existingMeeting" => $existingMeeting,
            "internalMeeting" => $internalMeetind,
            "other" => $other,
            "timezone" => $timezone
        ]);
    }
    public function actionCalendarText()
    {
        $year = $_POST["year"];
        $month = $_POST["month"];
        $day = $_POST["day"];
        $startDate = date("l", mktime(0, 0, 0, (int)$month, (int)$day, $year));

        $monthText = ModelMaster::monthEng($month, 1);
        $text = $startDate . ", " . $monthText . " $day," . " $year";
        $res["dateText"] = $text;
        return json_encode($res);
    }
}
