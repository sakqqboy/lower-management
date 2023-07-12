<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\KgiMaster;

/**
 * This is the model class for table "kgi".
 *
 * @property integer $kgiId
 * @property integer $branchId
 * @property string $kgiName
 * @property string $kgiDetail
 * @property string $unit
 * @property string $targetAmount
 * @property integer $amountType
 * @property string $sysbolCheck
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Kgi extends \frontend\models\lower_management\master\KgiMaster
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
    public static function unitName($unitId)
    {
        $unit = KgiUnit::find()->select('name')->where(["kgiUnitId" => $unitId])->asArray()->one();
        return  $unit["name"];
    }
    public static function amountFormat($kgiId)
    {
        $kgi = Kgi::find()->where(["kgiId" => $kgiId])->asArray()->one();
        $amount = number_format($kgi["targetAmount"], 2);
        $symbol = "";
        if ($kgi["symbolCheck"] == '1') {
            $symbol = " > ";
        }
        if ($kgi["symbolCheck"] == '2') {
            $symbol = " < ";
        }
        if ($kgi["symbolCheck"] == '3') {
            $symbol = " = ";
        }
        $type = $kgi["amountType"] == 1 ? '' : ' %';
        $text = $symbol . $amount . $type;
        return $text;
    }
    public static function kgiName($kgiId)
    {
        $kgi = Kgi::find()->where(["kgiId" => $kgiId])->asArray()->one();
        return $kgi["kgiName"];
    }
}
