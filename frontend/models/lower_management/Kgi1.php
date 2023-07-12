<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\Kgi1Master;

/**
 * This is the model class for table "kgi1".
 *
 * @property integer $kgi1Id
 * @property integer $branchId
 * @property string $kgi1Name
 * @property string $targetAmount
 * @property integer $amountType
 * @property string $code
 * @property integer $status
 * @property string $createDateTime
 * @property string $udpateDateTime
 * @property string $kgi1col
 */

class Kgi1 extends \frontend\models\lower_management\master\Kgi1Master
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
    public static function codeText($code)
    {
        $text = '';
        if ($code == 1) {
            $text = '>';
        }
        if ($code == 2) {
            $text = '<';
        }
        if ($code == 3) {
            $text = '=';
        }
        return $text;
    }
    public static function amountType($type)
    {
        $text = '';
        if ($type == 1) {
            $text = 'Number';
        }
        if ($type == 2) {
            $text = '%';
        }
        return $text;
    }
}
