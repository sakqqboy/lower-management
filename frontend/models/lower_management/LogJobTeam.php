<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogJobTeamMaster;

/**
* This is the model class for table "log_job_team".
*
* @property integer $id
* @property integer $jobId
* @property integer $teamId
* @property integer $currentStep
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogJobTeam extends \frontend\models\lower_management\master\LogJobTeamMaster{
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
