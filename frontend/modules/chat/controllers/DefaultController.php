<?php

namespace frontend\modules\chat\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Chart;
use frontend\models\lower_management\Chat;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\KeepNoti;
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
        $chat->parentId = $_POST["parent"];
        $chat->employeeId = Yii::$app->user->id;
        $chat->status = 1;
        $chat->createDateTime = new Expression('NOW()');

        $message = str_replace('\n', '<br>', $_POST["message"]);
        //throw new exception($message);
        $chat->message = $message;
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
        $keeps = KeepNoti::find()->where(["jobId" => $_POST["jobId"], "employeeId" => Yii::$app->user->id])->one();
        if (!isset($keeps) || empty($keeps)) {
            $keep = new KeepNoti();
            $keep->jobId = $_POST["jobId"];
            $keep->employeeId = Yii::$app->user->id;
            $keep->status = 1;
            $keep->createDateTime = new Expression('NOW()');
            $keep->updateDateTime = new Expression('NOW()');
            $keep->save(false);
        } else {
            $keeps->status = 1;
            $keeps->save(false);
        }
        $chatTime = Chat::find()->select('createDateTime,chatId,employeeId')->where(["chatId" => $chatId])->asArray()->one();
        $dateTime = explode(" ", $chatTime["createDateTime"]);
        $timeArr = explode(":",  $dateTime[1]);
        $time = '<div class="chat-time-me text-right">' .  $timeArr[0] . ':' . $timeArr[1] . '</div>';
        $classCancel = "text-left cancel-message";
        $menu = '<div id="context-' . $chatTime["chatId"] . '" class="chat-context-menu">
                       <a href="javascript:replyMessage(' . $chatTime["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Reply</div></a>
                       <a href="javascript:cancelMessage(' . $chatTime["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Cancel</div></a>
                </div>';

        $cancel = "<div class='" . $classCancel . "' id='cancel-" . $chatTime['chatId'] . "' style='display:none' ><i>* * Canceled * * </i></div>";
        $res["status"] = true;
        $res["lastId"] = $chatId;
        $res["parentId"] = '';
        if ($_POST["parent"] == '') {
            $res["chat"] = '<div class="row" id="chat' . $chatId . '">
                            <div class="col-lg-12">
                                <div class="text-left chat-message" oncontextmenu="javascript:chatRightClick(' . $chatTime["chatId"] . ',event)" id="contextChat-' . $chatTime["chatId"] . '">'
                . $message . '</div>
                                ' . $cancel . $time . '
                            </div>
                        </div>' . $menu;
        } else {

            $parent = Chat::find()->select('message,employeeId')->where(["chatId" => $_POST["parent"]])->asArray()->one();
            $name = ' <div class="col-12 chat-name-reply text-right">
            <i class="fa fa-share" aria-hidden="true"></i> Replied to ' . Employee::employeeName($parent["employeeId"])
                . '</div>';
            $pMessage = '<div class=" text-left chat-message-reply"><i>' . $parent["message"] . '</i></div>';
            $res["chat"] = '<a href="#chat' . $_POST["parent"] . '" class="no-underline">' . $name . '<div class="row">
                            <div class="col-12">
                            <div class="text-left chat-message" oncontextmenu="javascript:chatRightClick(' . $chatTime["chatId"] . ',event)" id="contextChat-' . $chatTime["chatId"] . '">'
                . $pMessage . $_POST["message"] . '</div>
                                ' . $cancel . $time . '
                            </div>
                        </div>' . $menu . '</a>';
            $res["parentId"] = $_POST["parent"];
        }
        return json_encode($res);
    }
    public function actionChat()
    {
        $message = '';
        $jobName = Job::jobName($_POST["jobId"]);
        $clientName = Job::clientName($_POST["jobId"]);
        $res["jobName"] = '<a href="' . Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $_POST["jobId"]]) . '" class="no-underline text-primary"><b>' . $jobName . '</b></a><div class="row col-10 font-size14">Client : ' . $clientName . '</div>';
        $chat = Chat::find()
            ->where(["jobId" => $_POST["jobId"]])
            ->andWhere("status!=99")
            ->asArray()->orderBy('createDateTime ASC')->all();
        $lastShowDate = "";
        if (isset($chat) && count($chat) > 0) {
            foreach ($chat as $ch) :
                $name = '';
                $time = '';
                $nameP = '';
                $pMessage = '';
                $chatName = Employee::employeeNameChat($ch["employeeId"]);
                $dateTime = explode(" ", $ch["createDateTime"]);
                $timeArr = explode(":",  $dateTime[1]);
                $showDate = ModelMaster::dateNumber($ch["createDateTime"]);
                if ($ch["parentId"] != null) {
                    $parent = Chat::find()->select('message,employeeId')->where(["chatId" => $ch["parentId"]])->asArray()->one();
                    if ($ch["employeeId"] == Yii::$app->user->id) { //if we answer our quesetion by our seft
                        $nameP = ' <div class="col-12 chat-name-reply text-right">
                    <i class="fa fa-share" aria-hidden="true"></i> Replied to ' . Employee::employeeName($parent["employeeId"])
                            . '</div>';
                        $pMessage = '<div class=" text-left chat-message-reply"><i>' . $parent["message"] . '</i></div>';
                    } else {
                        $nameP = '<div class="col-12 chat-name-reply text-left">
                        <i class="fa fa-share" aria-hidden="true"></i>  Replied to ' . Employee::employeeName($parent["employeeId"])
                            . '</div>';
                        $pMessage = '<div class=" text-left chat-message-reply"><i>' . $parent["message"] . '</i></div>';
                    }
                }
                if ($ch["employeeId"] == Yii::$app->user->id) {
                    $class = "text-left chat-message";
                    $classCancel = "text-left cancel-message";
                    $name =  '<div class="chat-name" id="employeeName' . $ch["chatId"] . '"></div>';
                    $time = '<div class="chat-time-me text-right">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                } else {
                    $class = "text-left chat-message-admin";
                    $classCancel = "text-left cancel-message-admin";
                    $name =  '<div class="chat-name" id="employeeName' . $ch["chatId"] . '">' . $chatName . '</div>';
                    $time = '<div class="chat-time text-left">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
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
                $status = $ch["status"];
                $styleCancel = $status == 1 ? 'none' : ' ';
                $styleMessage = $status == 0 ? 'none' : ' ';
                $cancel = "<div class='" . $classCancel . "' id='cancel-" . $ch['chatId'] . "' style='display:" . $styleCancel . "' ><i>* * Canceled * * </i></div>";
                if ($ch["employeeId"] == Yii::$app->user->id) {
                    $menu = '<div id="context-' . $ch["chatId"] . '" class="chat-context-menu">
                               <a href="javascript:replyMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Reply</div></a>
                               <a href="javascript:cancelMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Cancel</div></a>
                        </div>';
                } else {
                    $menu = '<div id="context-' . $ch["chatId"] . '" class="chat-context-menu-other">
                    <a href="javascript:replyMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Reply</div></a>
                    
             </div>';
                }
                if ($ch["employeeId"] == Yii::$app->user->id) {
                    if ($ch["parentId"] != null) {
                        $message .= '<a href="#chat' . $ch["parentId"] . '" class="no-underline">' . $nameP . '<div class="row" id="chat' . $ch["chatId"] . '">
                        <div class="col-12">'
                            . $name .
                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                            . $pMessage . $ch["message"] .
                            '</div>'
                            . $cancel . $time .
                            '</div>
            </div>' . $menu . '</a>';
                    } else {
                        $message .= $nameP . '<div class="row" id="chat' . $ch["chatId"] . '">
                                <div class="col-12">'
                            . $name .
                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                            . $pMessage . $ch["message"] .
                            '</div>'
                            . $cancel . $time .
                            '</div>
                    </div>' . $menu;
                    }
                } else {
                    if ($ch["parentId"] != null) {
                        $message .= '<a href="#chat' . $ch["parentId"] . '" class="no-underline">' . '<div class="row" id="chat' . $ch["chatId"] . '">
                        <div class="col-12">'
                            . $name . $nameP .
                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                            . $pMessage . $ch["message"] .
                            '</div>'
                            . $cancel . $time .
                            '</div>
            </div>' . $menu . '</a>';
                    } else {
                        $message .= '<div class="row" id="chat' . $ch["chatId"] . '">
                        <div class="col-12">'
                            . $name . $nameP .
                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                            . $pMessage . $ch["message"] .
                            '</div>'
                            . $cancel . $time .
                            '</div>
            </div>' . $menu;
                    }
                }


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
        if ($_POST["keep"] == 1) { //from head menu
            $keeps = KeepNoti::find()->where(["jobId" => $_POST["jobId"], "employeeId" => Yii::$app->user->id])->one();
            if (!isset($keeps) || empty($keeps)) {
                if (!isset($readChat) || empty($readChat)) {
                    $keep = new KeepNoti();
                    $keep->jobId = $_POST["jobId"];
                    $keep->employeeId = Yii::$app->user->id;
                    $keep->status = 1;
                    $keep->createDateTime = new Expression('NOW()');
                    $keep->updateDateTime = new Expression('NOW()');
                    $keep->save(false);
                }
            } else {
                $keeps->status = 1;
                $keeps->save(false);
            }
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
        $lastShowDate = "";
        if ($jobId != 0 && $jobId != null) {
            $lastChat = Chat::find()->select('chatId')
                ->where(["jobId" => $jobId, 'employeeId' => Yii::$app->user->id])
                ->andWhere("status!=99")
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
                        $chat = Chat::find()
                            ->where(["jobId" => $jobId])
                            ->andWhere("status!=99")
                            ->asArray()
                            ->orderBy('createDateTime ASC')
                            ->all();
                        if (isset($chat) && count($chat) > 0) {
                            foreach ($chat as $ch) :
                                $name = '';
                                $time = '';
                                $nameP = '';
                                $pMessage = '';
                                $chatName = Employee::employeeNameChat($ch["employeeId"]);
                                $dateTime = explode(" ", $ch["createDateTime"]);
                                $timeArr = explode(":",  $dateTime[1]);
                                $showDate = ModelMaster::dateNumber($ch["createDateTime"]);
                                if ($ch["parentId"] != null) {
                                    $parent = Chat::find()->select('message,employeeId')->where(["chatId" => $ch["parentId"]])->asArray()->one();
                                    if ($ch["employeeId"] == Yii::$app->user->id) { //if we answer our quesetion by our seft
                                        $nameP = ' <div class="col-12 chat-name-reply text-right">
                                    <i class="fa fa-share" aria-hidden="true"></i> Replied to ' . Employee::employeeName($parent["employeeId"])
                                            . '</div>';
                                        $pMessage = '<div class=" text-left chat-message-reply"><i>' . $parent["message"] . '</i></div>';
                                    } else {
                                        $nameP = '<div class="col-12 chat-name-reply text-left">
                                        <i class="fa fa-share" aria-hidden="true"></i>  Replied to ' . Employee::employeeName($parent["employeeId"])
                                            . '</div>';
                                        $pMessage = '<div class=" text-left chat-message-reply"><i>' . $parent["message"] . '</i></div>';
                                    }
                                }
                                if ($ch["employeeId"] == Yii::$app->user->id) {
                                    $class = "text-left chat-message";
                                    $classCancel = "text-left cancel-message";
                                    $time = '<div class="chat-time-me text-right">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
                                } else {
                                    $class = "text-left chat-message-admin";
                                    $classCancel = "text-left cancel-message-admin";
                                    $name =  '<div class="chat-name" id="employeeName' . $ch["chatId"] . '">' . $chatName . '</div>';
                                    $time = '<div class="chat-time text-left">' . $timeArr[0] . ':' . $timeArr[1] . '</div>';
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
                                $status = $ch["status"];
                                $styleCancel = $status == 1 ? 'none' : ' ';
                                $styleMessage = $status == 0 ? 'none' : ' ';
                                $cancel = "<div class='" . $classCancel . "' id='cancel-" . $ch['chatId'] . "' style='display:" . $styleCancel . "' ><i>* * Canceled * * </i></div>";
                                if ($ch["employeeId"] == Yii::$app->user->id) {
                                    $menu = '<div id="context-' . $ch["chatId"] . '" class="chat-context-menu">
                                               <a href="javascript:replyMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Reply</div></a>
                                               <a href="javascript:cancelMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Cancel</div></a>
                                        </div>';
                                } else {
                                    $menu = '<div id="context-' . $ch["chatId"] . '" class="chat-context-menu-other">
                                    <a href="javascript:replyMessage(' . $ch["chatId"] . ')" class="no-underline"><div class="col-12 list-chat-menu">Reply</div></a>
                                    
                             </div>';
                                }
                                if ($ch["employeeId"] == Yii::$app->user->id) {
                                    if ($ch["parentId"] != null) {
                                        $message .= '<a href="#chat' . $ch["parentId"] . '" class="no-underline">' . $nameP .
                                            '<div class="row" id="chat' . $ch["chatId"] . '">
                                                <div class="col-12">'
                                            . $name .
                                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                                            . $pMessage . $ch["message"] .
                                            '</div>'
                                            . $cancel . $time .
                                            '</div>
                                    </div>' . $menu . '</a>';
                                    } else {
                                        $message .= $nameP . '<div class="row" id="chat' . $ch["chatId"] . '">
                                        <div class="col-12">'
                                            . $name .
                                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                                            . $pMessage . $ch["message"] .
                                            '</div>'
                                            . $cancel . $time .
                                            '</div>
                            </div>' . $menu;
                                    }
                                } else {
                                    if ($ch["parentId"] != null) {
                                        $message .= '<a href="#chat' . $ch["parentId"] . '" class="no-underline"><div class="row" id="chat' . $ch["chatId"] . '">
                                                <div class="col-12">'
                                            . $name . $nameP .
                                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                                            . $pMessage . $ch["message"] .
                                            '</div>'
                                            . $cancel . $time .
                                            '</div>
                                    </div>' . $menu . '</a>';
                                    } else {
                                        $message .= '<div class="row" id="chat' . $ch["chatId"] . '">
                                        <div class="col-12">'
                                            . $name . $nameP .
                                            '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                                            . $pMessage . $ch["message"] .
                                            '</div>'
                                            . $cancel . $time .
                                            '</div>
                            </div>' . $menu;
                                    }
                                }
                            //             $message .= '<div class="row">
                            //             <div class="col-12">'
                            //                 . $name .
                            //                 '<div class="' . $class . '" oncontextmenu="javascript:chatRightClick(' . $ch["chatId"] . ',event)" id="contextChat-' . $ch["chatId"] . '" style="display:' . $styleMessage . '">'
                            //                 . $ch["message"] .
                            //                 '</div>'
                            //                 . $cancel . $time .
                            //                 '</div>
                            // </div>' . $menu;
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
    public function actionCountUnread()
    {
        $unread = Chat::unreadMessage();
        $res = [];
        if ($unread == $_POST["old"]) {
            $res["status"] = false;
            $res["unread"] = $unread;
        } else {
            $res["status"] = true;
            $res["unread"] = $unread;
        }
        return json_encode($res);
    }
    public function actionUnreadJobMessage()
    {
        $jobId = Chat::unreadMessageJob();
        $list = '';
        $res = [];
        // throw new Exception(print_r($jobId, true));
        if (count($jobId) > 0) {
            foreach ($jobId as $id => $status) :
                $job = Job::find()->select("job.jobName,c.clientName,rc.createDateTime,rc.employeeId,e.employeeNickName,job.jobId")
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "chat rc", "rc.jobId=job.jobId")
                    ->JOIN("LEFT JOIN", "employee e", "e.employeeId=rc.employeeId")
                    ->where(["job.jobId" => $id])
                    ->asArray()
                    ->one();
                if (isset($job) && !empty($job)) {
                    if ($status == 'keep') {
                        $list .= "<div class='col-12 list-message-noti' id='noti-" . $job['jobId'] . "' onClick='javascript:showChatBox(" . $job['jobId'] . ",1)'>
                        <b>" . $job['jobName'] . "</b><br>
                        Client : " . $job['clientName'] . "<br>
                        From : " . $job['employeeNickName'] . "</div>
                        <div class='col-12 text-right font-size12 footer-list foot-" . $job['jobId'] . "'>
                        " . ModelMaster::engDate($job['createDateTime'], 2) . "
                        <img src='" . Yii::$app->homeUrl . "images/icon/multiply.png' class='delete-list' id='unkeep' onClick='javascript:unkeep(" . $job['jobId'] . ")'>
                        </div>
                        ";
                    } else {
                        $list .= "<div class='col-12 list-message-noti' id='noti-" . $job['jobId'] . "' onClick='javascript:showChatBox(" . $job['jobId'] . ",1)'>
                        <b>" . $job['jobName'] . "</b><br>
                        Client : " . $job['clientName'] . "<br>
                        From : " . $job['employeeNickName'] . "</div>
                        <div class='col-12 text-right font-size12 footer-list '>" . ModelMaster::engDate($job['createDateTime'], 2) . "
                        <i class='fa fa-circle ml-20 text-primary font-size16' aria-hidden='true'></i>
                        </div>";
                    }
                }
            endforeach;
        }
        if ($list != '') {
            $res["status"] = true;
            $res["text"] = '<div class="row">' . $list . '</div>';
        } else {
            $res["status"] = false;
        }
        //throw new Exception(print_r($jobId, true));
        return json_encode($res);
    }
    public function actionUnkeep()
    {
        $keep = KeepNoti::find()->where(["jobId" => $_POST["jobId"], "employeeId" => Yii::$app->user->id])->one();
        if (isset($keep) && !empty($keep)) {
            $keep->delete();
        }
        $res["status"] = true;
        return json_encode($res);
    }
    public function actionCancelMessage()
    {
        $chatId = $_POST["chatId"];
        $chat = Chat::find()->where(["chatId" => $chatId])->one();
        $add = new Chat();
        $add->jobId = $chat->jobId;
        $add->employeeId = Yii::$app->user->id;
        $add->message = '';
        $add->status = 99;
        $add->createDateTime = new Expression('NOW()');
        $add->save(false);
        LastChatJob::deleteAll(['jobId' => $chat->jobId]);
        $lastChat = new LastChatJob();
        $lastChat->jobId = $chat->jobId;
        $lastChat->chatId = $chatId;
        $lastChat->createDateTime = new Expression('NOW()');
        $lastChat->save(false);
        $chat->status = 0;
        $chat->save(false);

        $res["status"] = true;
        return json_encode($res);
    }
    public function actionCheckUser()
    {
        $chat = Chat::find()->where(["chatId" => $_POST["chatId"]])->asArray()->one();
        if ($chat["employeeId"] == Yii::$app->user->id) {
            $res["own"] = 1;
        } else {
            $res["own"] = 0;
        }
        return json_encode($res);
    }
}
