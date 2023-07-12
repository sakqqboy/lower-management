var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/wikiinvestment/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;
/*$("#showQuestion").click(function() {
    var a = $("#showQuestion").val();
    
});*/

function showq(question) {
    var totalTopic = $("#totalTopic").val();

    $("#question" + question).show('fadeIn');
    $("#show" + question).hide();
    $("#hide" + question).show();
    var i = 1;
    while (i <= totalTopic) {
        if (i != question) {
            $("#question" + i).hide();
            $("#show" + i).show();
            $("#hide" + i).hide();
        }
        i++;
    }
}

function hideq(question) {
    $("#question" + question).hide('fadeout');
    $("#show" + question).show();
    $("#hide" + question).hide();
}
$("#reject").click(function() {
    $("#remark-box").css('display', '');
    var remark = $("#remark").val();
    if (remark == '') {
        return false;
    } else {
        $("#type").val('reject');
        $("#reject").addClass("disabled", "disabled");
        $("#detail").submit();
    }
});
$("#approve").click(function() {
    $("#type").val('approve');
    $("#approve").addClass("disabled", "disabled");
    $("#detail").submit();
});

function questionDelete(id) {
    var questionId = id;
    var url = $url + 'qna/import/delete-question';
    if (confirm('Are you sure to delete this news?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { id: questionId },
            success: function(data) {
                location.reload();
            }
        });
    }
}
//======================================================= news update ======================================================
function showN(i) {
    var totalNews = $("#totalNews").val();
    var totalMonth = $("#totalMonth").val();
    $("#year" + i).show('fadeIn');
    $("#showN" + i).hide();
    $("#hideN" + i).show();
    var a = 1;
    while (a <= totalNews) {
        if (a != i) {
            $("#year" + a).hide();
            $("#showN" + a).show();
            $("#hideN" + a).hide();
        }
        a++;
    }
}

function hideN(i) {
    $("#year" + i).hide('fadeout');
    $("#showN" + i).show();
    $("#hideN" + i).hide();
}

function showM(year, index) {

    $("#month" + year + "-" + index).show('fadeIn');
    $("#showM" + year + "-" + index).hide();
    $("#hideM" + year + "-" + index).show();
    var i = 1;
    while (i <= totalTopic) {
        if (i != question) {
            $("#question" + i).hide();
            $("#showM" + i).show();
            $("#hideM" + i).hide();
        }
        i++;
    }
}

function hideM(year, index) {
    $("#month" + year + "-" + index).hide('fadeout');
    $("#showM" + year + "-" + index).show();
    $("#hideM" + year + "-" + index).hide();
}
/*======================================================= member question ============================================*/
$("#reject-question").click(function() {
    $("#remark-box-question").css('display', '');
    $("#question-text-remark").css('display', '');
    var remark = $("#remark-box-question").val();
    var id = $("#mainId").val();
    if (remark == '') {
        return false;
    } else {
        var url = $url + 'qna/default/reject-member-question';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { id: id, remark: remark },
            success: function(data) {
                if (data.status) {
                    window.location.href = $url + 'qna/default/waiting-approve';
                } else {
                    alert('Please enter a remark');
                }

            }
        });
    }
});
$("#approve-question").click(function() {
    $("#type").val('approve');
    var url = $url + 'qna/default/approve-member-question';
    var question = $("#memberQuestionText").val();
    var answer = $("#summarize").val();
    var id = $("#mainId").val();
    var countryId = $("#forCountry").val();
    var questionTopicId = $("#questionTopicId").val();
    var subTopicId = $("#topicId").val();
    if (countryId != 0) {
        flag = 1;
        if ((typeof(questionTopicId) == "undefined") || (questionTopicId == null) || (questionTopicId == '')) { // มาจาก news  check subtopic
            //alert(subTopicId);
            if (subTopicId != 0) {
                var topicId = subTopicId;
            } else {
                alert('Please select topic');
                flag = 0;
            }
        } else { //มาจาก Question
            var topicId = questionTopicId;
        }
        if (flag == 1) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { id: id, answer: answer, question: question, countryId: countryId, topicId: topicId },
                success: function(data) {
                    if (data.status) {
                        window.location.href = $url + 'qna/default/waiting-approve';
                    }

                }
            });
        }
    } else {
        alert('Please select country');
    }

});
$("#update-question").click(function() {
    var url = $url + 'qna/default/update-member-question';
    var id = $("#memberQuestion").val();
    var question = $("#memberQuestionText").val();
    var answer = $("#answerMember").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { id: id, answer: answer, question: question },
        success: function(data) {
            if (data.status) {
                window.location.href = $url + 'qna/default/waiting-approve';
            }

        }
    });

});

function deleteMemberQuestion(id) {
    var questionId = id;
    var url = $url + 'qna/default/delete-member-question';
    if (confirm('Are you sure to delete this question?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { id: questionId },
            success: function(data) {
                if (data.status) {
                    location.reload();
                }
            }
        });
    }
}

function showComingsoon(id) {
    $("#pop" + id).show();
}

function hideComingsoon(id) {
    $("#pop" + id).hide();
}

function sentMoreQuestion() {
    var memberQuestionId = $("#mqId").val();
    var question = $("#moreQuestion").val();
    var url = $url + 'qna/default/add-more-question';

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { memberQuestionId: memberQuestionId, question: question },
        success: function(data) {
            if (data.status) {
                location.reload();
            } else {
                alert("Please contact the administrator");
            }
        }
    });
}

function sentAnswer() {
    var memberQuestionId = $("#qId").val();
    var answer = $("#answer").val();
    var url = $url + 'qna/additional/add-answer';
    // alert(memberQuestionId);
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { memberQuestionId: memberQuestionId, answer: answer },
        success: function(data) {
            if (data.status) {
                location.reload();
            } else {
                alert("Please contact the administrator");
            }
        }
    });
}

function sentMoreAnswer() {
    var memberQuestionId = $("#mqId").val();
    var answer = $("#moreAnswer").val();
    var url = $url + 'qna/additional/add-more-answer';

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { memberQuestionId: memberQuestionId, answer: answer },
        success: function(data) {
            if (data.status) {
                location.reload();
            } else {
                alert("Please contact the administrator");
            }
        }
    });
}

function completeQuestion() {
    var memberQuestionId = $("#mqId").val(); //parentId
    var url = $url + 'qna/additional/complete-question';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { memberQuestionId: memberQuestionId },
        success: function(data) {
            if (data.status) {
                location.reload();
            } else {
                alert("Please contact the administrator");
            }
        }
    });
}

function summarizeAnswer() {
    var memberQuestionId = $("#mainId").val(); //parentId
    var answer = $("#summarize").val();
    var countryId = $("#forCountry2").val();
    var topicId = $("#topicId2").val();
    var url = $url + 'qna/additional/summarize-answer';
    if (answer != '') {
        if (countryId == 0) {
            alert("Please choose country");
        } else if (topicId == 0) {
            alert("Please choose topic");
        } else {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { memberQuestionId: memberQuestionId, answer, answer, topicId: topicId, countryId: countryId },
                success: function(data) {
                    if (data.status) {
                        location.reload();
                    } else {
                        alert("Please summarize answer");
                    }
                }
            });
        }
    } else {
        alert("Please Summarize the answer");
    }
}

function editSummarizeAnswer() {
    var memberQuestionId = $("#mainId").val(); //parentId
    var answer = $("#summarize").val();
    var url = $url + 'qna/additional/summarize-answer';
    if (answer != '') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { memberQuestionId: memberQuestionId, answer, answer },
            success: function(data) {
                if (data.status) {
                    window.location.href = $url + 'qna/default/reject-question';
                } else {
                    alert("Please contact the administrator");
                }
            }
        });
    } else {
        alert("Please Summarize the answer");
    }
}

function createMemberQuestion() {
    var url = $url + 'news-update/default/create-question';
    var newsId = $("#nd").val();
    var questionId = $("#qd").val();
    var videoId = $("#vd").val()
    var question = $("#userQuestion").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { newsId: newsId, question: question, questionId: questionId, videoId: videoId },
        success: function(data) {

        }
    });
}