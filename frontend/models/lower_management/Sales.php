<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\SalesMaster;

/**
* This is the model class for table "sales".
*
* @property integer $salesId
* @property string $title
* @property string $date
* @property string $clientName
* @property string $planceWayId
* @property string $clientAttendance
* @property string $clientEmail
* @property string $explanation
* @property integer $branchId
* @property integer $teamId
* @property integer $employeeId
* @property integer $isConfirm
* @property integer $isSales
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Sales extends \frontend\models\lower_management\master\SalesMaster{
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
