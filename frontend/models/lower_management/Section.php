<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\SectionMaster;
use yii\db\Expression;

/**
 * This is the model class for table "section".
 *
 * @property integer $sectionId
 * @property string $sectionName
 * @property string $sectionDetail
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDatetime
 */

class Section extends \frontend\models\lower_management\master\SectionMaster
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
    public static function sectionName($sectionId)
    {
        $section = Section::find()->select('sectionName')->where(["sectionId" => $sectionId])->asArray()->one();
        if (isset($section)) {
            return $section["sectionName"];
        } else {
            return 'Section not set';
        }
    }
    public static function sectionId($sectionName, $branchId)
    {
        $section = Section::find()
            ->select('sectionId')
            ->where(["sectionName" => $sectionName, "branchId" => $branchId])
            ->asArray()
            ->one();
        if (isset($section) && !empty($section)) {
            return $section["sectionId"];
        } else {
            $section = new Section();
            $section->sectionName = $sectionName;
            $section->branchId = $branchId;
            $section->status = Section::STATUS_ACTIVE;
            $section->createDateTime = new Expression('NOW()');
            $section->updateDateTime = new Expression('NOW()');
            $section->save(false);
            $sectionId = Yii::$app->db->lastInsertID;
            return $sectionId;
        }
    }
}
