<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\ClientMaster;
use yii\db\Expression;

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

class Client extends \frontend\models\lower_management\master\ClientMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    const STATUS_DELETED = 99;
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
    public static function clientName($clientId)
    {
        $client = Client::find()->select('clientName')->where(["clientId" => $clientId])->asArray()->one();
        return $client["clientName"];
    }
    public static function checkNewClient($branchId, $clientName)
    {
        $client = Client::find()
            ->select('clientName')
            ->where(["branchId" => $branchId, "clientName" => $clientName])
            ->one();
        if (isset($client) && !empty($client)) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function clientStatus($clientId)
    {
        $client = Client::find()->select('status')->where(["clientId" => $clientId])->asArray()->one();
        if (isset($client) && !empty($client)) {
            if ($client["status"] == Client::STATUS_ACTIVE) {
                return 'Active';
            }
            if ($client["status"] == Client::STATUS_DISABLED) {
                return 'Disable';
            }
            if ($client["status"] == Client::STATUS_DELETED) {
                return 'Deleted';
            }
        } else {
            return "-";
        }
    }
    public static function clientNameExcel($clientId)
    {
        $client = Client::find()->select('clientName')->where(["clientId" => $clientId])->asArray()->one();
        $clientName = '';
        if (str_contains($client["clientName"], '&')) {
            $nameArr = explode('&', $client["clientName"]);
            foreach ($nameArr as $name) :
                $clientName .= $name . ' ';
            endforeach;
            return $clientName;
        } else {
            return $client["clientName"];
        }
    }
    public static function isExistClient($clientName, $branchId)
    {
        $client = Client::find()->where(["clientName" => $clientName, "branchId" => $branchId])->one();
        if (isset($client) && !empty($client)) {
            return $client->clientId;
        } else {

            $client = new Client();
            $client->clientName = $clientName;
            $client->branchId = $branchId;
            $client->status = 1;
            $client->createDateTime = new Expression('NOW()');
            $client->updateDateTime = new Expression('NOW()');
            $client->save(false);
            $clientId = Yii::$app->db->lastInsertID;
            return $clientId;
        }
    }
}
