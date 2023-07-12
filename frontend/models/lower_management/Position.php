<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\PositionMaster;
use yii\db\Expression;

/**
 * This is the model class for table "position".
 *
 * @property integer $positionId
 * @property string $positionName
 * @property string $positionDetail
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Position extends \frontend\models\lower_management\master\PositionMaster
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
    public static function positionName($positionId)
    {
        $position = position::find()->select('positionName')->where(["positionId" => $positionId])->asArray()->one();
        if (isset($position)) {
            return $position["positionName"];
        } else {
            return 'Position not set';
        }
    }
    public static function positionId($positionName, $branchId)
    {
        $position = Position::find()
            ->select('positionId')
            ->where(["positionName" => $positionName, "branchId" => $branchId])
            ->asArray()
            ->one();
        if (isset($position) && !empty($position)) {
            return $position["positionId"];
        } else {
            $position = new Position();
            $position->positionName = $positionName;
            $position->branchId = $branchId;
            $position->status = Section::STATUS_ACTIVE;
            $position->createDateTime = new Expression('NOW()');
            $position->updateDateTime = new Expression('NOW()');
            $position->save(false);
            $positionId = Yii::$app->db->lastInsertID;
            return $positionId;
        }
    }
}
