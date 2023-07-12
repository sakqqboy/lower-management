<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\TeamMaster;
use yii\db\Expression;

/**
 * This is the model class for table "team".
 *
 * @property integer $teamId
 * @property string $teamName
 * @property integer $sectionId
 * @property string $teamDetail
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Team extends \frontend\models\lower_management\master\TeamMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 99;
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
    public static function teamName($teamId)
    {
        $team = Team::find()->select('teamName')->where(["teamId" => $teamId])->asArray()->one();
        if (isset($team)) {
            return $team["teamName"];
        } else {
            return 'team not set';
        }
    }
    public static function teamNameExcel($teamId)
    {
        $teamName = '';
        if ($teamId != '') {
            $team = Team::find()->select('teamName')->where(["teamId" => $teamId])->asArray()->one();
            if (isset($team)) {
                $teamName = $team["teamName"];
            }
        }
        return $teamName;
    }
    public static function teamId($teamName, $branchId)
    {
        $team = Team::find()
            ->select('teamId')
            ->where(["teamName" => $teamName, "branchId" => $branchId, "status" => Team::STATUS_ACTIVE])
            ->asArray()
            ->one();
        if (isset($team) && !empty($team)) {
            return $team["teamId"];
        } else {
            $team = new Team();
            $team->teamName = $teamName;
            $team->branchId = $branchId;
            $team->status = Section::STATUS_ACTIVE;
            $team->createDateTime = new Expression('NOW()');
            $team->updateDateTime = new Expression('NOW()');
            $team->save(false);
            $teamId = Yii::$app->db->lastInsertID;
            return $teamId;
        }
    }
    public static function teamNameFilter($teamId)
    {
        $team = Team::find()->select('teamName')->where(["teamId" => $teamId])->asArray()->one();
        if (isset($team)) {
            return $team["teamName"];
        } else {
            return 'Team';
        }
    }
    public static function teamId2($teamName, $branchId)
    {
        $team = Team::find()
            ->select('teamId')
            ->where(["teamName" => $teamName, "branchId" => $branchId, "status" => Team::STATUS_ACTIVE])
            ->asArray()
            ->one();
        if (isset($team) && !empty($team)) {
            return $team["teamId"];
        } else {
            return '';
        }
    }
}
