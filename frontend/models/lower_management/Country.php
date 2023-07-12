<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\CountryMaster;

/**
 * This is the model class for table "country".
 *
 * @property integer $countryId
 * @property string $countryName
 * @property integer $continentId
 * @property string $flag
 * @property string $lat
 * @property string $lng
 * @property integer $hasBranch
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Country extends \frontend\models\lower_management\master\CountryMaster
{
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
    public static function countryName($countryId)
    {
        $country = Country::find()->select('countryName')->where(["countryId" => $countryId])->asArray()->one();
        if (isset($country)) {
            return $country["countryName"];
        } else {
            return 'country not set';
        }
    }
}
