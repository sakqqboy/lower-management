<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\TeamMaster;

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

class Team extends \common\models\lower_management\master\TeamMaster{
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
