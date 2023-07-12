<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\CurrencyMaster;

/**
 * This is the model class for table "currency".
 *
 * @property integer $currencyId
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property integer $status
 */

class Currency extends \frontend\models\lower_management\master\CurrencyMaster
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
    public static function currencyNameFull($currencyId)
    {
        $currencyText = '<span class="text-danger">* * * Please set currency * * *</span>';
        if ($currencyId != '' && $currencyId != null) {
            $currency = Currency::find()->where(["currencyId" => $currencyId])->asArray()->one();
            if (isset($currency) && !empty($currency)) {
                $currencyText = $currency["name"] . '&nbsp;&nbsp;(' . $currency["name"] . '&nbsp;&nbsp;' . $currency["symbol"] . ')';
            }
        }
        return $currencyText;
    }
    public static function currencyCode($currencyId)
    {
        $code = '';
        if ($currencyId != '') {
            $currency = Currency::find()->where(["currencyId" => $currencyId])->asArray()->one();
            if (isset($currency) && !empty($currency)) {
                $code = $currency["code"];
            }
        } else {
            $code = '-';
        }
        return $code;
    }
    public static function currencyId($currencyCode)
    {

        $currency = Currency::find()->where(["code" => $currencyCode])->asArray()->one();
        if (isset($currency) && !empty($currency)) {
            $code = $currency["currencyId"];
        } else {
            $code = '';
        }
        return $code;
    }
}
