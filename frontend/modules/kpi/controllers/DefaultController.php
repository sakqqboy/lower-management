<?php

namespace frontend\modules\kpi\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\KgiGroup;
use frontend\models\lower_management\KgiUnit;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\KpiUnit;
use frontend\models\lower_management\Team;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

/**
 * Default controller for the `kpi` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $kgi = Kgi::find()->where(["status" => 1])->asArray()->orderBy('branchId,createDateTime')->all();
        $kgiGroups = KgiGroup::find()->where(["status" => 1])->asArray()->orderBy('kgiGroupName')->all();
        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])
            ->asArray()
            ->orderBy('branchName')
            ->all();

        return $this->render('index', [
            "kgi" => $kgi,
            "branch" => $branch,
            "kgiGroups" => $kgiGroups
        ]);
    }

    public function actionCreateKgi()
    {
        if (isset($_POST["branch"])) {
            $kgi = new Kgi();
            $kgi->branchId = $_POST["branch"];
            $kgi->teamId = $_POST["team"];
            $kgi->kgiGroupId = $_POST["kgiGroup"];
            $kgi->teamPositionId = $_POST["teamPosition"];
            $kgi->kgiName = $_POST["kgiName"];
            $kgi->kgiDetail = $_POST["kgiDetail"];
            $kgi->unit = $_POST["unit"];
            $kgi->targetAmount = $_POST["targetAmount"];
            $kgi->symbolCheck = $_POST["check"];
            $kgi->amountType = $_POST["type"];
            $kgi->status = 1;
            $kgi->createDateTime = new Expression('NOW()');
            $kgi->updateDateTime = new Expression('NOW()');
            if ($kgi->save(false)) {
                return $this->redirect('index');
            }
        }
        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])
            ->asArray()
            ->orderBy('branchName')
            ->all();
        $kgiUnit = KgiUnit::find()->where(["status" => 1])->orderBy('kgiUnitId')->asArray()->all();
        $kgiGroups = KgiGroup::find()->where(["status" => 1])->asArray()->orderBy('kgiGroupName')->all();
        return $this->render('create_kgi', [
            "branch" => $branch,
            "kgiUnit" => $kgiUnit,
            "kgiGroups" => $kgiGroups
        ]);
    }
    public function actionUpdateKgi($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $kgiId = $param["kgiId"];
        $kgi = Kgi::find()->where(["kgiId" => $kgiId])->one();
        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])
            ->asArray()
            ->orderBy('branchName')
            ->all();
        $kgiGroups = KgiGroup::find()->where(["status" => 1])->asArray()->orderBy('kgiGroupName')->all();
        $kgiUnit = KgiUnit::find()->where(["status" => 1])->orderBy('kgiUnitId')->asArray()->all();
        $teams = Team::find()->where(["branchId" => $kgi["branchId"], "status" => 1])->asArray()->orderBy('teamName')->all();


        return $this->render('update_kgi', [
            "branch" => $branch,
            "kgiUnit" => $kgiUnit,
            "kgi" => $kgi,
            "teams" => $teams,
            "kgiGroups" => $kgiGroups
        ]);
    }
    public function actionSaveUpdateKgi()
    {
        if (isset($_POST["branch"])) {
            $kgi = Kgi::find()->where(["kgiId" => $_POST["kgiId"]])->one();
            $kgi->branchId = $_POST["branch"];
            $kgi->teamId = $_POST["team"];
            $kgi->kgiGroupId = $_POST["kgiGroup"];
            $kgi->teamPositionId = $_POST["teamPosition"];
            $kgi->kgiName = $_POST["kgiName"];
            $kgi->kgiDetail = $_POST["kgiDetail"];
            $kgi->unit = $_POST["unit"];
            $kgi->targetAmount = $_POST["targetAmount"];
            $kgi->symbolCheck = $_POST["check"];
            $kgi->amountType = $_POST["type"];
            $kgi->status = 1;
            $kgi->updateDateTime = new Expression('NOW()');
            if ($kgi->save(false)) {
                return $this->redirect('index');
            }
        }
    }
    public function actionDeleteKgi()
    {
        $kpi = Kpi::find()->where(["kgiId" => $_POST["kgiId"]])->all();
        if (isset($kpi) && count($kpi) > 0) {
            foreach ($kpi as $k) :
                $k->status = 99;
                $k->save(false);
            endforeach;
        }
        $kgi = Kgi::find()->where(["kgiId" => $_POST["kgiId"]])->one();
        $kgi->status = 99;
        $kgi->save(false);
        $res["status"] = true;
        return json_encode($res);
    }
    public function actionCreateKpi($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $kgiId = $param["kgiId"];
        $kgi = Kgi::find()->where(["kgiId" => $kgiId])->asArray()->one();
        $kpiUnit = KpiUnit::find()->where(["status" => 1])->orderBy('kpiUnitId')->asArray()->all();
        return $this->render('create_kpi', [
            "kgi" => $kgi,
            "kpiUnit" => $kpiUnit
        ]);
    }
    public function actionSaveKpi()
    {
        if (isset($_POST["kpiName"])) {
            $kpi = new Kpi();
            $kpi->kpiName = $_POST["kpiName"];
            $kpi->kpiDetail = $_POST["kpiDetail"];
            $kpi->unit = $_POST["unit"];
            $kpi->kgiId = $_POST["kgiId"];
            $kpi->targetAmount = $_POST["targetAmount"];
            $kpi->symbolCheck = $_POST["check"];
            $kpi->amountType = $_POST["type"];
            $kpi->status = 1;
            $kpi->createDateTime = new Expression('NOW()');
            $kpi->updateDateTime = new Expression('NOW()');
            if ($kpi->save(false)) {
                return $this->redirect(Yii::$app->homeUrl . 'kpi/default/kgi-detail/' . ModelMaster::encodeParams(["kgiId" => $_POST["kgiId"]]));
            } else {
                return $this->redirect(Yii::$app->homeUrl . 'kpi/default/create-kpi/' . ModelMaster::encodeParams(["kgiId" => $_POST["kgiId"]]));
            }
        }
    }
    public function actionUpdateKpi($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $kpiId = $param["kpiId"];
        $kpi = Kpi::find()->where(["kpiId" => $kpiId])->one();
        $kgi = Kgi::find()->where(["kgiId" => $kpi->kpiId])->asArray()->one();
        $kpiUnit = KpiUnit::find()->where(["status" => 1])->orderBy('kpiUnitId')->asArray()->all();
        return $this->render('update_kpi', [
            "kpi" => $kpi,
            "kgi" => $kgi,
            "kpiUnit" => $kpiUnit
        ]);
    }
    public function actionSaveUpdateKpi()
    {
        if (isset($_POST["kpiId"])) {
            $kpi = Kpi::find()->where(["kpiId" => $_POST["kpiId"]])->one();
            $kgiId = $kpi->kgiId;
            $kpi->kpiName = $_POST["kpiName"];
            $kpi->kpiDetail = $_POST["kpiDetail"];
            $kpi->unit = $_POST["unit"];
            $kpi->targetAmount = $_POST["targetAmount"];
            $kpi->symbolCheck = $_POST["check"];
            $kpi->amountType = $_POST["type"];
            $kpi->status = 1;
            $kpi->updateDateTime = new Expression('NOW()');
            if ($kpi->save(false)) {
                return $this->redirect(Yii::$app->homeUrl . 'kpi/default/kgi-detail/' . ModelMaster::encodeParams(["kgiId" => $kgiId]));
            }
        }
    }
    public function actionDeleteKpi()
    {
        $kpi = Kpi::find()->where(["kpiId" => $_POST["kpiId"]])->one();
        $kpi->status = 99;
        $kpi->save(false);
        $res["status"] = true;
        return json_encode($res);
    }
    public function actionKgiDetail($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $kgi = Kgi::find()->where(["kgiId" => $param["kgiId"]])->asArray()->one();
        $kpi = Kpi::find()->where(["kgiId" => $param["kgiId"], "status" => 1])->asArray()->orderBy('createDateTime ASC')->all();
        return $this->render('kpi_detail', [
            "kgi" => $kgi,
            "kpi" => $kpi
        ]);
    }
    public function actionTeamBranch()
    {
        $branchId = $_POST["branchId"];
        $teams = Team::find()->where(["branchId" => $branchId, "status" => 1])->asArray()->orderBy('teamName')->all();
        $textTeam = '<option value="">Dream Team</option>';
        if (isset($teams) && count($teams) > 0) {
            foreach ($teams as $team) :
                $textTeam .= "<option value='" . $team['teamId'] . "'>" . $team['teamName'] . "</option>";
            endforeach;
        }
        $res["textTeam"] = $textTeam;
        return json_encode($res);
    }
    public function actionCreateKgiGroup()
    {
        if (isset($_POST["kgiGroupName"]) && trim($_POST["kgiGroupName"]) != '') {
            $kgiGroup = new KgiGroup();
            $kgiGroup->kgiGroupName = $_POST["kgiGroupName"];
            $kgiGroup->status = 1;
            $kgiGroup->createDateTime = new Expression('NOW()');
            $kgiGroup->updateDateTime = new Expression('NOW()');
            if ($kgiGroup->save(false)) {
                return $this->redirect('create-kgi-group');
            }
        }
        $kgiGroups = KgiGroup::find()->where(["status" => 1])->orderBy('createDateTime ASC')->asArray()->all();
        return $this->render('create_group', [
            "kgiGroups" => $kgiGroups
        ]);
    }
    public function actionUpdateKgiGroup($hash)
    {
        $param = ModelMaster::decodeParams($hash);
        $kgiGroup = KgiGroup::find()->where(["kgiGroupId" => $param["kgiGroupId"]])->asArray()->one();
        return $this->render('update_kgi_group', [
            "kgiGroup" => $kgiGroup
        ]);
    }
    public function actionSaveUpdateKgiGroup()
    {
        $kgiGroup = KgiGroup::find()->where(["kgiGroupId" => $_POST["kgiGroupId"]])->one();
        $kgiGroup->kgiGroupName = $_POST["kgiGroupName"];
        $kgiGroup->status = 1;
        $kgiGroup->updateDateTime = new Expression('NOW()');
        if ($kgiGroup->save(false)) {
            return $this->redirect('create-kgi-group');
        }
    }
    public function actionDeleteKpiGroup()
    {
        $kgiGroup = KgiGroup::find()->where(["kgiGroupId" => $_POST["kgiGroupId"]])->one();
        $kgiGroup->status = 99;
        $kgiGroup->save(false);
        $res["status"] = true;
        return json_encode($res);
    }
    public function actionFilterKgi()
    {
        $text = '';
        $kgi = Kgi::find()
            ->where(["status" => 1])
            ->andFilterWhere([
                "branchId" => $_POST["branchId"],
                "teamId" => $_POST["teamId"],
                "teamPositionId" => $_POST["teamPositionId"],
                "kgiGroupId" => $_POST["kgiGroupId"]
            ])
            ->asArray()
            ->orderBy('branchId,createDateTime')
            ->all();
        //throw new Exception(print_r($kgi, true));
        $text = $this->renderAjax('search_result', [
            "kgi" => $kgi
        ]);
        $res["textResult"] = $text;
        return json_encode($res);
    }
}
