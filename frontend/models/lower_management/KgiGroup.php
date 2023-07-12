<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\KgiGroupMaster;

/**
 * This is the model class for table "kgi_group".
 *
 * @property integer $kgiGroupId
 * @property string $kgiGroupName
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class KgiGroup extends \frontend\models\lower_management\master\KgiGroupMaster
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
    public static function kgiGroupName($kgiGroupId)
    {
        $kgiGroup = KgiGroup::find()->where(["kgiGroupId" => $kgiGroupId])->asArray()->one();
        return $kgiGroup["kgiGroupName"];
    }
}
