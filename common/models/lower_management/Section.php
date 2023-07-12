<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\SectionMaster;

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

class Section extends \common\models\lower_management\master\SectionMaster{
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
}
