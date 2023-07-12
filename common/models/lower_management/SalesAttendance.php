<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\SalesAttendanceMaster;

/**
* This is the model class for table "sales_attendance".
*
* @property integer $saleAttendanceId
* @property integer $employeeId
* @property integer $typeId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class SalesAttendance extends \common\models\lower_management\master\SalesAttendanceMaster{
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
