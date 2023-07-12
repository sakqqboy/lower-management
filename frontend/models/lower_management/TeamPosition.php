<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\TeamPositionMaster;

/**
 * This is the model class for table "team_position".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class TeamPosition extends \frontend\models\lower_management\master\TeamPositionMaster
{
    /**
     * @inheritdoc
     */
    const LEADER = 1;
    const SUBLEADER = 2;
    const STAFF = 3;
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
    public static function positionName($id)
    {
        $team = TeamPosition::find()->select('name')->where(["id" => $id])->asArray()->one();
        if (isset($team)) {
            return $team["name"];
        } else {
            return 'Not set';
        }
    }
    public static function positionId($positionName)
    {
        if ($positionName != '') {
            $team = TeamPosition::find()->select('id')->where(["name" => $positionName])->asArray()->one();
            return $team["id"];
        } else {
            return null;
        }
    }
}
