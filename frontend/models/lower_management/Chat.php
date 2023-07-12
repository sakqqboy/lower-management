<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\ChatMaster;
use yii\db\Expression;

/**
 * This is the model class for table "chat".
 *
 * @property integer $chatId
 * @property integer $jobId
 * @property integer $employeeId
 * @property string $messege
 * @property integer $status
 * @property string $createDateTime
 */

class Chat extends \frontend\models\lower_management\master\ChatMaster
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
    public static function unreadMessage()
    {
        $employeeType = EmployeeType::findEmployeeType();
        $isManger = 0;
        $isSupervisor = 0;
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $rightBranch = [Type::TYPE_MANAGER];
        $unread = 0;
        if (count($employeeType) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $isManger = 1;
                }
                if (in_array($all, $rightBranch)) {
                    $isSupervisor = 1;
                }
            endforeach;
        }
        if ($isManger == 1) {
            $messages = Chat::find()->select('jobId')->where("message IS NOT NULL")->asArray()->groupBy('jobId')->all();
        } else if ($isSupervisor == 1) {
            $branchId = Employee::employeeBranch();
            $messages = Chat::find()->select('chat.jobId')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=chat.jobId")
                ->where(["j.branchId" => $branchId])
                ->andWhere("chat.message IS NOT NULL and j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->orderBy('chat.createDateTime DESC')
                ->groupBy('chat.jobId')
                ->all();
        } else {
            $teamId = Employee::employeeTeam();
            $messages = Chat::find()->select('chat.jobId')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=chat.jobId")
                ->where(["j.teamId" => $teamId])
                ->andWhere("chat.message IS NOT NULL and j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->groupBy('chat.jobId')
                ->all();
        }
        //  throw new Exception(print_r($messages, true));
        if (isset($messages) && count($messages) > 0) {
            foreach ($messages as $message) :
                $readChat = ReadChat::find()
                    ->where(["jobId" => $message["jobId"], "employeeId" => Yii::$app->user->id])
                    ->one();
                if (!isset($readChat) || empty($readChat)) {
                    $unread++;
                }
            endforeach;
        }
        return $unread;
    }
    public static function unreadMessageJob()
    {
        $employeeType = EmployeeType::findEmployeeType();
        $isManger = 0;
        $isSupervisor = 0;
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $rightBranch = [Type::TYPE_MANAGER];
        $unread = [];
        if (count($employeeType) > 0) {

            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $isManger = 1;
                }
                if (in_array($all, $rightBranch)) {
                    $isSupervisor = 1;
                }
            endforeach;
        }

        if ($isManger == 1) {
            $messages = Chat::find()
                ->select('jobId')
                ->where("message IS NOT NULL")
                ->asArray()
                ->orderBy('createDateTime DESC')
                ->groupBy('jobId')
                ->all();
        } else if ($isSupervisor == 1) {
            $branchId = Employee::employeeBranch();
            $messages = Chat::find()->select('chat.jobId')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=chat.jobId")
                ->where(["j.branchId" => $branchId])
                ->andWhere("chat.message IS NOT NULL and j.status!=" . Job::STATUS_DELETED, "chat.status!=99")
                ->asArray()
                ->orderBy('chat.createDateTime DESC')
                ->groupBy('chat.jobId')
                ->all();
        } else {
            $teamId = Employee::employeeTeam();
            $messages = Chat::find()->select('chat.jobId')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=chat.jobId")
                ->where(["j.teamId" => $teamId])
                ->andWhere("chat.message IS NOT NULL and j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->orderBy('chat.createDateTime DESC')
                ->groupBy('chat.jobId')
                ->all();
        }

        if (isset($messages) && count($messages) > 0) {

            foreach ($messages as $message) :
                $readChat = ReadChat::find()
                    ->where(["jobId" => $message["jobId"], "employeeId" => Yii::$app->user->id])
                    ->one();
                if (!isset($readChat) || empty($readChat)) {
                    $unread[$message["jobId"]] = 'unread';
                }
            endforeach;
        }
        $keeps = KeepNoti::find()
            ->where(["status" => 1, "employeeId" => Yii::$app->user->id])
            ->orderBy('createDateTime DESC')
            ->all();
        if (isset($keeps) && count($keeps) > 0) {
            foreach ($keeps as $k) :
                if (!array_key_exists($k["jobId"], $unread)) {
                    $unread[$k["jobId"]] = 'keep';
                }
            endforeach;
        }
        return $unread;
    }
}
