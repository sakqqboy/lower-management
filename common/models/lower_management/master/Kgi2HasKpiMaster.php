<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi2_has_kpi".
*
    * @property integer $id
    * @property integer $kgi2Id
    * @property integer $kpiId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi2HasKpiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi2_has_kpi';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi2Id', 'kpiId'], 'required'],
            [['kgi2Id', 'kpiId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'kgi2Id' => 'Kgi2id',
    'kpiId' => 'Kpi ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
