<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\Kgi2CategoryMaster;

/**
* This is the model class for table "kgi2_category".
*
* @property integer $kgi2CategoryId
* @property integer $kgi2Id
* @property integer $fiscalYear
* @property integer $month
* @property string $actualAmount
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi2Category extends \frontend\models\lower_management\master\Kgi2CategoryMaster{
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
