<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\PersonalKgiMaster;
use yii\db\Expression;

/**
 * This is the model class for table "personal_kgi".
 *
 * @property integer $personalKgiId
 * @property integer $kgiId
 * @property string $targetAmount
 * @property string $personalTargetAmount
 * @property integer $employeeId
 * @property integer $status
 * @property string $createDateTime
 * @property string $udateDateTime
 */

class PersonalKgi extends \frontend\models\lower_management\master\PersonalKgiMaster
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
    public static function setPernalkgi($kgiId, $employeeId)
    {
        $kgi = Kgi::find()->where(["kgiId" => $kgiId])->one();
        $personalKgi = PersonalKgi::find()->where(["kgiId" => $kgiId, "employeeId" => $employeeId])->one();
        if (isset($personalKgi) && !empty($personalKgi)) {
            $personalKgi->targetAmount = $kgi["targetAmount"];
            $personalKgi->save(false);
        } else {
            $personalKgi = new PersonalKgi();
            $personalKgi->kgiId = $kgiId;
            $personalKgi->targetAmount = $kgi->targetAmount;
            $personalKgi->personalTargetAmount = $kgi->targetAmount;
            $personalKgi->employeeId = $employeeId;
            $personalKgi->status = 1;
            $personalKgi->createDateTime = new Expression('NOW()');
            $personalKgi->updateDateTime = new Expression('NOW()');
            $personalKgi->save(false);
        }
    }
}
