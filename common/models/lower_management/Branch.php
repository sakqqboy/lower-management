<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\BranchMaster;

/**
* This is the model class for table "branch".
*
* @property integer $branchId
* @property string $branchName
* @property integer $countryId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Branch extends \common\models\lower_management\master\BranchMaster{
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
