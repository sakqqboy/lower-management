<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\KeepNotiMaster;

/**
* This is the model class for table "keep_noti".
*
* @property integer $keepNotiId
* @property integer $jobId
* @property integer $employeeId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class KeepNoti extends \frontend\models\lower_management\master\KeepNotiMaster{
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
