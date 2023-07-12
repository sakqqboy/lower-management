<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\ReadChatMaster;

/**
 * This is the model class for table "read_chat".
 *
 * @property integer $id
 * @property integer $jobId
 * @property integer $employeeId
 * @property string $createDateTime
 */

class ReadChat extends \frontend\models\lower_management\master\ReadChatMaster
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
    public static function checkNewChat($jobId)
    {
        $jobChat = Chat::find()->where(["jobId" => $jobId])->one();
        if (isset($jobChat) && !empty($jobChat)) {
            $read = ReadChat::find()->where(["employeeId" => Yii::$app->user->id, "jobId" => $jobId])->one();
            if (isset($read) && !empty($read)) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
    }
}
