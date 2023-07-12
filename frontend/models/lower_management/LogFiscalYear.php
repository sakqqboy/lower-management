<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogFiscalYearMaster;

/**
* This is the model class for table "log_fiscal_year".
*
* @property integer $id
* @property integer $jobCategoryId
* @property integer $oldFiscalYear
* @property integer $newFiscalYear
* @property integer $status
* @property string $createDateDTime
* @property string $updateDateTime
*/

class LogFiscalYear extends \frontend\models\lower_management\master\LogFiscalYearMaster{
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
