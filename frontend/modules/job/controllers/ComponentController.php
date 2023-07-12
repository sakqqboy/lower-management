<?php

namespace frontend\modules\job\controllers;

use Exception;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\Team;
use Yii;
use yii\web\Controller;

class ComponentController extends Controller
{
	public function actionRedirectJob()
	{
		$url = Yii::$app->homeUrl . 'job/detail/index';
		return $this->redirect($url);
	}
	public function actionRedirectCarlendar()
	{
		$url = Yii::$app->homeUrl . 'job/carlendar/index';
		return $this->redirect($url);
	}
	public function actionReward()
	{

		$jobs = Job::find()->where(["branchId" => 1, "status" => [1, 4]])->asArray()->all();
		$teams = Team::find()
			->where(["status" => 1, "branchId" => 1])
			->andWhere("teamId!=9 and teamId!=12")
			->asArray()->all();
		if (isset($teams) && count($teams) > 0) {
			foreach ($teams as $team) :
				$star[$team["teamId"]] = 0;
				$checkList[$team["teamId"]] = 0;
				$manual[$team["teamId"]] = 0;
				$none[$team["teamId"]] = 0;
			endforeach;
		}

		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				if ($job["teamId"] != 9 && $job["teamId"] != 12) {
					if (trim($job["checkListPath"]) != "" && trim($job["url"]) != '') {
						if (isset($star[$job["teamId"]])) {
							$star[$job["teamId"]]++;
						} else {
							$star[$job["teamId"]] = 1;
						}
					}
					if (trim($job["checkListPath"]) != "" && trim($job["url"]) == '') {
						if (isset($checkList[$job["teamId"]])) {
							$checkList[$job["teamId"]]++;
						} else {
							$checkList[$job["teamId"]] = 1;
						}
					}
					if (trim($job["checkListPath"]) == "" && trim($job["url"]) != '') {
						if (isset($manual[$job["teamId"]])) {
							$manual[$job["teamId"]]++;
						} else {
							$manual[$job["teamId"]] = 1;
						}
					}
					if (trim($job["checkListPath"]) == "" && trim($job["url"]) == '') {
						if (isset($none[$job["teamId"]])) {
							$none[$job["teamId"]]++;
						} else {
							$none[$job["teamId"]] = 1;
						}
					}
				}
			endforeach;
		}
		arsort($star);
		//throw new Exception(print_r($teams, true));
		//$teams = Team::find()->where(["branchId" => 1, "status" => 1])->asArray()->orderBy('teamName')->all();
		$teamReward = [];
		//if (isset($teams) && count($teams) > 0) {
		foreach ($star as $teamId => $team) :
			$teamReward[$teamId] = [
				"star" => isset($star[$teamId]) ? $star[$teamId] : 0,
				"checkList" => isset($checkList[$teamId]) ? $checkList[$teamId] : 0,
				"manual" => isset($manual[$teamId]) ? $manual[$teamId] : 0,
				"none" => isset($none[$teamId]) ? $none[$teamId] : 0,
			];
		endforeach;
		//}


		//throw new Exception(print_r($teamReward, true));
		return $this->render('reward', ["teamReward" => $teamReward]);
	}
}
