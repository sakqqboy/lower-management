<?php

namespace frontend\modules\chat\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Chat;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\LastChatJob;
use frontend\models\lower_management\ReadChat;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

/**
 * Default controller for the `chat` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        $chat = new Chat();
        $chat->jobId = $_POST["jobId"];
        $chat->message = $_POST["message"];
        $chat->employeeId = Yii::$app->user->id;
        $chat->status = 1;
        $chat->createDateTime = new Expression('NOW()');
        $chat->save(false);
        $chatId = Yii::$app->db->lastInsertID;
        LastChatJob::deleteAll(['jobId' => $_POST["jobId"]]);
        $lastChat = new LastChatJob();
        $lastChat->jobId = $_POST["jobId"];
        $lastChat->chatId = $chatId;
        $lastChat->createDateTime = new Expression('NOW()');
        $lastChat->save(false);
        ReadChat::deleteAll(["jobId" => $_POST["jobId"]]);
        $readChat = new ReadChat();
        $readChat->jobId = $_POST["jobId"];
        $readChat->employeeId = Yii::$app->user->id;
        $readChat->createDateTime = new Expression('NOW()');
        $readChat->save(false);
        $chatTime = Chat::find()->select('createDateTime')->where(["chatId" => $chatId])->asArray()->one();
        $dateTime = explode(" ", $chatTime["createDateTime"]);
        $timeArr = explode(":",  $dateTime[1]);
        $time = '<div class="chat-time-me text-right">' .  $timeArr[0] . ':' . $timeArr[1] . '</div>';
        $res["status"] = true;
        $res["lastId"] = $chatId;
        $res["chat"] = '<div class="row">
                            <div class="col-lg-12">
                                <div class="text-left chat-message">' . $_POST["message"] . '</div>
                                ' . $time . '
                            </div>
                        </div>';
        return json_encode($res);
    }
    public function actionChat()
    {
        $message = '';

        $jobName = Job::jobName($_POST["jobId"]);
        $res["jobName"] = '<b>' . $jobName . '</b>';
        $chat = Chat::find()->where(["jobId" => $_POST["jobId"]])->asArray()->orderBy('createDateTime ASC')->all();
        $lastShowDate = "";
        if (isset($chat) && count($chat) > 0) {
            foreach ($chat as $ch) :
                $name = '';
                $time = '';
                $chatName = Employee::employeeNameChat($ch["employeeId"]);
                $dateTime = explode(" ", $ch["createDateTime"]);
                $timeArr = explode(":",  $dateTime[1]);
                $showDate = ModelMaster::dateNumber($ch["createDateTime"]);
                if ($ch["employeeId"] == Yii::$app->user->id) {
                    $class = "text-left chat-message";
                    $time = '<div class="chat-time-me text-right">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                } else {
                    $class = "text-left chat-message-admin";
                    $name =  '<div class="chat-name">' . $chatName . '</div>';
                    $time = '<div class="chat-time text-right">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                }
                if ($lastShowDate != $showDate) {
                    $show = 1;
                    $lastShowDate = $showDate;
                } else {
                    $show = 0;
                }
                if ($show == 1) {
                    $message .= '<div class="row mt-20 mb-10">
                <div class="col-12 text-center chat-name">' . $lastShowDate . '</div></div>';
                }
                $message .= '<div class="row">
                <div class="col-12">' . $name . '
                <div class="' . $class . '">' . $ch["message"] . '</div>' . $time .
                    '</div>
                </div>';

            endforeach;
        }
        $readChat = ReadChat::find()->where(["jobId" => $_POST["jobId"], "employeeId" => Yii::$app->user->id])->one();
        if (!isset($readChat) || empty($readChat)) {
            $read = new ReadChat();
            $read->jobId = $_POST["jobId"];
            $read->employeeId = Yii::$app->user->id;
            $read->createDateTime = new Expression('NOW()');
            $read->save(false);
        }
        $last = Chat::find()->select('chatId')
            ->where(["jobId" => $_POST["jobId"]])
            ->asArray()
            ->orderBy('createDateTime DESC')
            ->one();
        if (isset($last) && !empty($last)) {
            $lastId = $last["chatId"];
        } else {
            $lastId = 0;
        }
        $res["status"] = true;
        $res["chat"] = $message;
        $res["lastId"] = $lastId;
        return json_encode($res);
    }
    public function actionChatRealtime()
    {
        $jobId = $_POST["jobId"];
        $res = [];
        $message = '';
        $res["status"] = false;
        if ($jobId != 0 && $jobId != null) {
            $lastChat = Chat::find()->select('chatId')->where(["jobId" => $jobId, 'employeeId' => Yii::$app->user->id])
                ->asArray()
                ->orderBy('createDateTime DESC')
                ->one();
            $readChat = ReadChat::find()->where(["jobId" => $_POST["jobId"], "employeeId" => Yii::$app->user->id])->one();
            if (!isset($readChat) || empty($readChat)) {
                $read = new ReadChat();
                $read->jobId = $_POST["jobId"];
                $read->employeeId = Yii::$app->user->id;
                $read->createDateTime = new Expression('NOW()');
                $read->save(false);
            }
            if (isset($lastChat) && !empty($lastChat)) {
                $lastChatId = $lastChat["chatId"];
                $lastJobChat = LastChatJob::find()->select('chatId')->where(["chatId" => $lastChatId])->one();
                if (isset($lastJobChat) && !empty($lastJobChat)) {
                    $res["status"] = false; //there are no new message
                } else {
                    $lastAll = Chat::find()->select('chatId')->where(["jobId" => $jobId])
                        ->asArray()
                        ->orderBy('createDateTime DESC')
                        ->one();
                    if (isset($lastAll) && !empty($lastAll)) {
                        $lastChatDb = $lastAll["chatId"];
                    } else {
                        $lastChatDb = 0;
                    }
                    if ($lastChatDb != $_POST["lastChatId"]) { //ฝั่งรับไม่เท่ากับฝั่งส่ง(has new message)
                        $chat = Chat::find()->where(["jobId" => $jobId])->asArray()->orderBy('createDateTime ASC')->all();
                        if (isset($chat) && count($chat) > 0) {
                            foreach ($chat as $ch) :
                                $name = '';
                                $time = '';
                                $chatName = Employee::employeeNameChat($ch["employeeId"]);
                                $dateTime = explode(" ", $ch["createDateTime"]);
                                $timeArr = explode(":",  $dateTime[1]);
                                if ($ch["employeeId"] == Yii::$app->user->id) {
                                    $class = "text-right chat-message";
                                    $time = '<div class="chat-time-me text-right">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                                } else {
                                    $class = "text-left chat-message-admin";
                                    $name =  '<div class="chat-name text-left">' . $chatName . '</div>';
                                    $time = '<div class="chat-time text-left">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                                }

                                $message .= '<div class="row">
                            <div class="col-12">' . $name . '
                            <div class="' . $class . '">' . $ch["message"] . '</div>' . $time .
                                    '</div>
                            </div>';
                            endforeach;
                            $res["status"] = true;
                            $res["chat"] = $message;
                            $res["lastId"] = $lastChatDb;
                        }
                    } else {
                        $res["status"] = false;
                    }
                }
            }
        }
        return json_encode($res);
    }
}
