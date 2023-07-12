var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$("#close-chat").click(function() {
    $("#chat-icon").show();
    $("#chat-box").hide();
    $("#isClose").val(1);
    $("#jobName").html('');
    $("#showChat").html('');
    $("#chatbox").val('');

});

function showChatBox(jobId, keep) {

    $("#chat-box").show();
    $("#jobChatId").val(jobId);
    $("#isClose").val(0);
    $("#noit-chat-" + jobId).css("display", "none");
    $(".message-noti-box").css("display", "none");
    $("#reply-message").css("display", "none");
    var url = $url + 'chat/default/chat';
    $("#reply-message").html('');
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId, keep: keep },
        success: function(data) {
            if (data.status) {
                $("#showChat").html(data.chat);
                $('#showChat').scrollTop($('#showChat')[0].scrollHeight);
                $("#jobName").html(data.jobName);
                $("#lastChatId").val(data.lastId);
            }
        }
    });
    var myIntervalc = setInterval(() => {
        var isclose = $("#isClose").val();
        if (isclose == 1) {
            clearInterval(myIntervalc);
        } else {
            realtimeChat(jobId);
        }
    }, 3000);
}

function realtimeChat(jobId) {
    var url = $url + 'chat/default/chat-realtime';
    var lastChatId = $("#lastChatId").val();
    $.ajax({
        type: "POST",
        dataType: 'JSON',
        url: url,
        data: { jobId: jobId, lastChatId: lastChatId },
        success: function(data) {
            if (data.status) {
                if (data.chat) {
                    $("#showChat").html(data.chat);
                    var scroll = $('#showChat').scrollTop();
                    var height = $('#showChat')[0].scrollHeight;
                    //alert(height + '==>' + scroll);
                    var total = parseInt(height - scroll);
                    //alert(total);
                    if (total == 463) { //box hight400 each chat+margin=63
                        $('#showChat').scrollTop($('#showChat')[0].scrollHeight);

                    } else {
                        // $("#new-message").show();
                    }
                    $("#showChat").html(data.chat);
                    $("#lastChatId").val(data.lastId);
                }
            } else {
                var scroll = $('#showChat').scrollTop();
                var height = $('#showChat')[0].scrollHeight;
                var total = parseInt(height - scroll);
                // alert(total);
                if (total == 400) {
                    $("#new-message").css("display", "none");
                }
            }

        }
    });
}
$("#chatbox").on('keyup ', function(e) {
    var element = document.getElementById("showChat");
    var parent = $("#chatParent").val();
    if (e.which == 13 && !e.shiftKey) {
        element.scrollTop = element.scrollHeight;
        var message = $("#chatbox").val();
        var jobId = $("#jobChatId").val();
        $("#chatbox").val('');
        var url = $url + 'chat/default/index';
        if ($.trim(message) != '') {
            //alert(message);
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { message: message, jobId: jobId, parent: parent },
                success: function(data) {
                    if (data.status) {
                        $("#showChat").append(data.chat);
                        $('#showChat').scrollTop($('#showChat')[0].scrollHeight);
                        $("#lastChatId").val(data.lastId);
                        if (data.parentId != '') {
                            $("#reply-message").css("display", "none");
                        }
                        $("#chatParent").val('')
                    }
                }
            });
        }
    }
    if (e.keyCode === 13 && e.shiftKey) {
        $("#chatbox").val().replace("\n", " ");
    }

    //$("#sent-message").val(message + '<br>');
    //alert('adfadfad');
    //if (e.type == "keypress") {
    //$("#chatbox").html('<br>');
    // }

});
$("#new-message").on('click', function(e) {
    $('#showChat').scrollTop($('#showChat')[0].scrollHeight);
    $("#new-message").css("display", "none");
});
$("#new-noti").on('click ', function(e) {
    var url = $url + 'chat/default/unread-job-message';
    var old = $("#old-unread").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { old: old },
        success: function(data) {
            if (data.status) {
                $("#unread-noti").html(data.text);
                $(".message-noti-box").show();
            }
        }
    });
});
$(window).ready(function(e) {
    var url = $url + 'chat/default/count-unread';
    var old = $("#old-unread").val();
    var myInterval = setInterval(() => {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { old: old },
            success: function(data) {
                if (data.status) {
                    if (data.unread >= 0) {
                        $("#total-unread").html(data.unread);
                        $("#old-unread").val(data.unread);
                        if (data.unread == 0) {
                            $("#unread-message").css("display", "none");
                            $(".text-massege").css("margin-top", "10px");
                        } else {
                            $(".text-massege").css("margin-top", "1px");
                            $("#unread-message").show();
                        }
                    }
                } else {
                    if (old == 0) {
                        $("#unread-message").css("display", "none");
                        $(".text-massege").css("margin-top", "10px");
                        $("#old-unread").val(0);
                    } else {
                        $("#total-unread").html(data.unread);
                        $("#old-unread").val(data.unread);
                    }
                }
            }
        });
    }, 3000);
});

function unkeep(jobId) {
    var url = $url + 'chat/default/unkeep';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId },
        success: function(data) {
            if (data.status) {
                $("#noti-" + jobId).css('display', 'none');
                $(".foot-" + jobId).css('display', 'none');
            }
        }
    });
}

function chatRightClick(chatId, event) {
    var lastShow = $("#lastShowChatId").val();

    let x = event.clientX;
    let y = event.clientY;
    var menu = document.getElementById("context-" + chatId);
    if (lastShow != 0) {
        $("#context-" + lastShow).css("display", "none");
    }
    var url = $url + 'chat/default/check-user';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { chatId: chatId },
        success: function(data) {
            if (data.own == 1) {
                menu.style.display = 'inline-block';
                menu.style.top = y + 10 + "px";
            } else {
                menu.style.display = 'inline-block';
                menu.style.left = x + "px";
                menu.style.top = y + 10 + "px";
            }
        }
    });

    $("#lastShowChatId").val(chatId);
}

function replyMessage(chatId) {
    var message = $("#contextChat-" + chatId).text();
    $("#reply-message").html('');
    $("#reply-message").show();
    $("#chatParent").val(chatId);
    let length = message.length;
    if (length > 100) {
        var cutText = message.substring(0, 100) + '...';
    } else {
        var cutText = message;
    }
    var name = $("#employeeName" + chatId).text();
    if (name == '') {
        name = 'You';
    }
    var close = '<div class="col-12 text-right box-close-reply"><i class="fa fa-times close-reply" aria-hidden="true" id="close-reply' + chatId + '" onclick="javascript:closeReply(' + chatId + ')"></i></div>';
    $("#reply-message").html(close + '<b>Reply :</b> ' + name + '<br>' + cutText);
    $("#chatbox").focus();
    $("#context-" + chatId).css("display", "none");
}

function closeReply(chatId) {
    $("#reply-message").html('');
    $("#reply-message").css("display", "none");
    $("#chatParent").val('');
}

function cancelMessage(chatId) {
    if (confirm('Are you sure to cancel this message?')) {
        var url = $url + 'chat/default/cancel-message';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { chatId: chatId },
            success: function(data) {
                if (data.status) {
                    $("#cancel-" + chatId).show();
                    $("#contextChat-" + chatId).css("display", "none");
                    $("#lastShowChatId").val(chatId);
                    $("#context-" + chatId).css("display", "none");
                }
            }
        });
    }
}