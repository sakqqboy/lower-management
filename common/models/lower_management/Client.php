<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ClientMaster;

/**
* This is the model class for table "client".
*
* @property integer $clientId
* @property string $clientName
* @property string $clientAddress
* @property string $clientTel1
* @property string $clientTel2
* @property string $email
* @property string $taxId
* @property string $coordinator
* @property string $fisicalYear
* @property integer $isHeadOffice
* @property integer $countryId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Client extends \common\models\lower_management\master\ClientMaster{
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
