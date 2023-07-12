<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\Kgi2Master;

/**
 * This is the model class for table "kgi2".
 *
 * @property integer $kgi2Id
 * @property string $kgi2Name
 * @property integer $branchId
 * @property integer $teamId
 * @property integer $teamPositionId
 * @property string $targetAmount
 * @property string $code
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Kgi2 extends \frontend\models\lower_management\master\Kgi2Master
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
    public static function dreamTeam($kgi2Id)
    {
        $kgi2 = Kgi2Team::find()->where(["status" => 1, "kgi2Id" => $kgi2Id])->asArray()->groupBy('teamId')->all();
        $teamName = '';
        if (isset($kgi2) && count($kgi2) > 0) {
            $i = 1;
            foreach ($kgi2 as $kgi) :
                $team = Team::find()->select('teamName')->where(["teamId" => $kgi["teamId"]])->asArray()->one();
                $teamName .= $i . '. ' . $team["teamName"] . '<br>';
                $i++;
            endforeach;
        }

        return $teamName;
    }
    public static function dreamTeamPosition($kgi2Id)
    {
        $kgi2 = Kgi2Team::find()->where(["status" => 1, "kgi2Id" => $kgi2Id])->groupBy('teamPositionId')->asArray()->all();
        $positionName = '';
        if (isset($kgi2) && count($kgi2) > 0) {
            $i = 1;
            foreach ($kgi2 as $kgi) :
                $team = TeamPosition::find()->select('name')->where(["id" => $kgi["teamPositionId"]])->asArray()->one();
                $positionName .= $i . '. ' . $team["name"] . '<br>';
                $i++;
            endforeach;
        }
        return $positionName;
    }
    public static function mainKgi1($kgi2Id)
    {
        $kgi2 = Kgi1HasKgi2::find()->where(["kgi2Id" => $kgi2Id, "isMain" => 1])->asArray()->one();
        $kgi1Name = '';
        if (isset($kgi2) && !empty($kgi2)) {
            $kgi1 = Kgi1::find()->select("kgi1Name")->where(["kgi1Id" => $kgi2["kgi1Id"]])->asArray()->one();
            $kgi1Name = $kgi1["kgi1Name"];
        }
        return $kgi1Name;
    }
    public static function countKgi1($kgi2Id)
    {
        $kgi2 = Kgi1HasKgi2::find()->where(["kgi2Id" => $kgi2Id, "status" => 1])->asArray()->all();
        return count($kgi2);
    }
    public static function AllKpi($kgi2Id)
    {
        $kpi = Kgi2HasKpi::find()->where(["status" => 1, "kgi2Id" => $kgi2Id])->all();
        return count($kpi);
    }
}
