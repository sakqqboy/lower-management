<?php
/**
 * Created by PhpStorm.
 * User: npr
 * Date: 3/1/2016 AD
 * Time: 4:27 PM
 */

namespace backend\modules\KTGenerator\models;

use yii\base\Model;


class ModelGenerator extends Model
{
    public $dbName;
    public $nameSpace;
    public $folderName;
    public $baseClass = 'yii\db\ActiveRecord';

    public function attributeLabels()
    {
        return [
            'dbName' => 'DB Name',
            'nameSpace' => 'Name Space',
            'folderName' => 'folder Name',
        ];
    }

    public function rules()
    {
        return [
            [['dbName', 'nameSpace', 'folderName'], 'required'],
        ];
    }
}
