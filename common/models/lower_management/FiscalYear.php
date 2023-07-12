<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\FiscalYearMaster;

/**
* This is the model class for table "fiscal_year".
*
* @property integer $fiscalYearId
* @property integer $fiscalYear
* @property string $createDateTime
* @property string $closeDateTime
* @property integer $status
* @property string $updateDateTime
*/

class FiscalYear extends \common\models\lower_management\master\FiscalYearMaster{
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
