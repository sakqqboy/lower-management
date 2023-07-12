var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;
$("#job-branch").change(function() {
    var url = $url + 'job/default/job-type-branch';
    var branchId = $("#job-branch").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            $("#jobType").removeAttr("disabled");
            $("#jobType").html(data.text);
            if (branchId != '') {
                $("#dream-team-job").removeAttr("disabled");
                $("#dream-team-job").html(data.textTeam);
                $("#field-job").html(data.textField);
                $("#job-client-id").html(data.textClient);
            } else {
                $("#dream-team-job").attr("disabled", "disabled");
                $("#dream-team-job").html("<option value=''>Dream Team</option>");
                $("#field-job").html("<option value=''>Field</option>");
                $("#morePic1-0").attr("disabled", "disabled");
                $("#morePic2-0").attr("disabled", "disabled");
                $("#percentagePic1-0").val('');
                $("#percentagePic2-0").attr('');
                $("#percentagePic1-0").attr("disabled", "disabled");
                $("#percentagePic2-0").attr("disabled", "disabled");
                $("#approver").attr("disabled", "disabled");

            }
        }
    });

});

$("#jobType").change(function() {
    var url = $url + 'job/default/job-type-step';
    var jobTypeId = $("#jobType").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobTypeId: jobTypeId },
        success: function(data) {
            $("#step-due-date").html(data.text);
        }
    });
});
$("#job-category").change(function() {
    var url = $url + 'job/default/job-category-layout';
    var categoryId = $("#job-category").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { categoryId: categoryId },
        success: function(data) {
            $("#month-set").html(data.text);
        }
    });
});
$("#dream-team-job").change(function() {
    var teamId = $("#dream-team-job").val();
    var branchId = $("#job-branch").val();
    var url = $url + 'job/default/pic-team';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { teamId: teamId, branchId: branchId },
        success: function(data) {
            $("#morePic1-0").html(data.text1);
            $("#morePic2-0").html(data.text2);
            $("#approver").html(data.text3);
            $("#morePic1-0").removeAttr("disabled");
            $("#morePic2-0").removeAttr("disabled");
            $("#percentagePic1-0").removeAttr("disabled");
            $("#percentagePic2-0").removeAttr("disabled");
            $("#approver").removeAttr("disabled");
            if (teamId == '') {
                $("#morePic1-0").attr("disabled", "disabled");
                $("#morePic2-0").attr("disabled", "disabled");
                $("#percentagePic1-0").val('');
                $("#percentagePic2-0").attr('');
                $("#percentagePic1-0").attr("disabled", "disabled");
                $("#percentagePic2-0").attr("disabled", "disabled");
                $("#approver").attr("disabled", "disabled");
            }
        }
    });

});
$("#client-name").keyup(function() {
    var clientName = $("#client-name").val();
    var url = $url + 'job/default/existing-client';
    $("#clientId").val(null);
    if (clientName != '') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { clientName: clientName },
            success: function(data) {
                if (data.status) {
                    $("#existClientName").html(data.text);
                    $("#existClientName").css("display", "inline");
                }
            }
        });
    } else {
        $("#existClientName").css("display", "none");
    }
});

function existClient(id) {
    $("#clientId").val(id);
    var text = $("#client-" + id).text();
    $("#client-name").val(text);
    $("#existClientName").css("display", "none");
}

function showMonthCalendar(i) {
    $("#month-calendar" + i).css("display", "inline");
}

function addValue(i, month) {
    $("#startMonth" + i).val(month);
    $("#month-calendar" + i).css("display", "none");
}
$("#add-pic1").click(function() {
    var lastPic1 = $("#lastPIC1").val();
    var previous = parseInt(lastPic1) - 1;
    var nextPic1 = parseInt(lastPic1) + 1;
    var previousValue = $("#morePic1-" + previous).val();
    var teamId = $("#dream-team-job").val();
    var branchId = $("#job-branch").val();
    var news = "<div class='col-9 mt-10'><select class='form-control' id='morePic1-" + lastPic1 + "' name='pIc1[]'> <option>PIC 1</option></select></div><div class='col-2 mt-10'><input type='text' name='percentagePic1[]' id='percentagePic1-" + lastPic1 + "' class='form-control text-right p-1' onKeyUp='if(isNaN(this.value)){this.value=" + null + ";}'></div><div class='col-1'></div>";
    if (previousValue != '') {
        $("#add-more-pic1").append(news);
        var url = $url + 'job/default/user-type';
        var typeName = "PIC 1";
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { typeName: typeName, branchId: branchId, teamId: teamId },
            success: function(data) {
                $("#morePic1-" + lastPic1).html(data.text);
                $("#lastPIC1").val(nextPic1);
            }
        });
    } else {
        alert('Please select current PIC1 !');
    }
});
$("#add-pic2").click(function() {
    var lastPic2 = $("#lastPIC2").val();
    var previous = parseInt(lastPic2) - 1;
    var nextPic2 = parseInt(lastPic2) + 1;
    var previousValue = $("#morePic2-" + previous).val();
    var teamId = $("#dream-team-job").val();
    var branchId = $("#job-branch").val();
    var news = "<div class='col-9 mt-10'><select class='form-control' id='morePic2-" + lastPic2 + "' name='pIc2[]'> <option>PIC 2</option></select></div><div class='col-2 mt-10'><input type='text' name='percentagePic2[]' id='percentagePic2-" + lastPic2 + "' class='form-control text-right p-1' onKeyUp='if(isNaN(this.value)){this.value=" + null + ";}'></div><div class='col-1'></div>";
    if (previousValue != '') {
        $("#add-more-pic2").append(news);
        var url = $url + 'job/default/user-type';
        var typeName = "PIC 2";
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { typeName: typeName, branchId: branchId, teamId: teamId },
            success: function(data) {
                $("#morePic2-" + lastPic2).html(data.text);
                $("#lastPIC2").val(nextPic2);
            }
        });
    } else {
        alert('Please select current PIC2 !');
    }
});

function checkCreateJob() {
    var maxPic1 = $("#lastPIC1").val(); //ใช้ -1
    var maxPic2 = $("#lastPIC2").val(); //ใช้ -1
    var pic1 = parseInt(maxPic1) - 1;
    var pic2 = parseInt(maxPic2) - 1;
    var percentPIC1 = 0;
    var percentPIC2 = 0;
    var totalPercent = 0;
    setTimeout(
        function() {
            if (pic1 > 0) {
                var i = 0;
                while (i <= pic1) {
                    if ($("#percentagePic1-" + i).val() == '') {
                        percentPIC1 = parseInt(percentPIC1) + 0;
                    } else {
                        percentPIC1 = parseInt(percentPIC1) + parseInt($("#percentagePic1-" + i).val());
                    }
                    i++;
                }
            } else {
                percentPIC1 = $("#percentagePic1-0").val()
            }
            if (pic2 > 0) {
                var i = 0;
                while (i <= pic2) {
                    if ($("#percentagePic2-" + i).val() == '') {
                        percentPIC2 = parseInt(percentPIC2) + 0;
                    } else {
                        percentPIC2 = parseInt(percentPIC2) + parseInt($("#percentagePic2-" + i).val());
                    }
                    i++;
                }
            } else {
                percentPIC2 = $("#percentagePic2-0").val()
            }
            totalPercent = parseInt(percentPIC1) + parseInt(percentPIC2);
            if (totalPercent > 100) {
                alert('Incorrect Percentage!');
                $("#create-job-form").on("submit", function(e) {
                    e.preventDefault();
                });
            }
        }, 100);
}
$("#branch-search-job").change(function() {
    var branchId = $("#branch-search-job").val();
    var url = $url + 'job/detail/search-filter';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#team-job").html(data.textTeam);
                $("#client-search-job").html(data.textClient);
                $("#jobType-search").html(data.textJobType);
            }

        }
    });

    //filterJob();
});
$("#team-job").change(function() {
    var teamId = $("#team-job").val();
    var url = $url + 'job/detail/search-filter-team';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { teamId: teamId },
        success: function(data) {
            if (data.status) {
                $("#user-type-search-employee").html(data.textPerson);
            }

        }
    });

    //filterJob();
});

function sortStepDue() {
    var currentValue = $("#sort-step").val();
    if (currentValue == 0) {
        $("#sort-step").val(1);
    }
    if (currentValue == 1) {
        $("#sort-step").val(2);
    }
    if (currentValue == 2) {
        $("#sort-step").val(1);
    }
    $("#sort-final").val(0);
    filterJob();
}

function sortFinalDue() {
    var currentValue = $("#sort-final").val();
    if (currentValue == 0) {
        $("#sort-final").val(1);
    }
    if (currentValue == 1) {
        $("#sort-final").val(2);
    }
    if (currentValue == 2) {
        $("#sort-final").val(1);
    }
    $("#sort-step").val(0);
    filterJob();
}

function filterJob() {
    var branchId = $("#branch-search-job").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var clientId = $("#client-search-job").val();
    var groupFieldId = $("#group-field-search-job").val();
    //var status = $("#status-search").val();
    var jobTypeId = $("#jobType-search").val();
    var sortStep = $("#sort-step").val();
    var sortFinal = $("#sort-final").val();
    var report = 0;
    if ($("#report").prop("checked") == true) {
        report = 1;
    } else {
        report = 0;
    }
    var url = $url + 'job/detail/search-job';
    var i = 0;
    var status = [];
    $(".loading").show();
    while (i < 4) {
        if ($("#status-search" + i).prop("checked") == true) {
            status[i] = $("#status-search" + i).val();
        }
        i++;
    }

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, status: status, clientId: clientId, groupFieldId: groupFieldId, jobTypeId: jobTypeId, sortStep: sortStep, sortFinal: sortFinal, report: report },
        success: function(data) {
            if (data) {
                //$("#job-result").html(data.text);
            }

        }
    });
}

function exportJob() {
    var branchId = $("#branch-search-job").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var clientId = $("#client-search-job").val();
    var groupFieldId = $("#group-field-search-job").val();
    //var status = $("#status-search").val();
    var jobTypeId = $("#jobType-search").val();
    var sortStep = $("#sort-step").val();
    var sortFinal = $("#sort-final").val();
    var needReport = null;
    if ($("#report").prop("checked") == true) {
        needReport = 1;
    }
    var url = $url + 'job/detail/prepare';
    var i = 0;
    var status = [];
    while (i < 4) {
        if ($("#status-search" + i).prop("checked") == true) {
            status[i] = $("#status-search" + i).val();
        }
        i++;
    }

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, status: status, clientId: clientId, groupFieldId: groupFieldId, jobTypeId: jobTypeId, sortStep: sortStep, sortFinal: sortFinal, needReport: needReport },
        success: function(data) {
            // if (data.status) {
            //     window.location.assign(url + window.location.hostname + 'file/export/' + data["fileName"]);
            // }
        }
    });
}
$("#show-carlendar").change(function() {
    var page = $("#show-carlendar").val();
    var branchId = $("#branch-search-job").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var status = $("#status-search").val();
    if (page == 1) {
        var url = $url + 'job/detail/search-job';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, status: status },
            success: function(data) {}
        });
    }
    if (page == 2) {
        var url = $url + 'job/component/redirect-carlendar';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { page: page },
            success: function(data) {
                if (data) {}

            }
        });
    }
});

function showAddComment(jobStepId) {
    $("#modal-comment").fadeIn(300);
    $("#commentJobStep").val(jobStepId);
    var url = $url + 'job/detail/job-step-comment';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId },
        success: function(data) {
            $("#comment").val(data.text);
        }
    });
}

function saveComment() {
    var jobStepId = $("#commentJobStep").val();
    var comment = $("#comment").val();
    var url = $url + 'job/detail/save-comment';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId, comment: comment },
        success: function(data) {
            if (data.status) {
                $("#modal-comment").css("display", "none");
                // location.reload();
            }
        }
    });
}

function showComment(jobStepId) {
    $("#modal-show-comment").fadeIn(300);
    var url = $url + 'job/detail/show-comment';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId },
        success: function(data) {
            $("#jobStep-comment").html(data.text);
        }
    });
}
$("#add-complain").click(function() {
    $("#modal-complain").fadeIn(300);
});
$(".close-modal").click(function() {
    $("#modal-complain").css("display", "none");
    $("#modal-reason").css("display", "none");
    $("#modal-target-date").css("display", "none");
    $("#modal-fiscal-year").css("display", "none");
    $("#modal-document").css("display", "none");
    $("#modal-cancel-detail").css("display", "none");
    $("#modal-job").css("display", "none");
    $("#modal-comment").css("display", "none");
    $("#modal-show-comment").css("display", "none");
    $("#jobStep-comment").html('');
    $("#commentJobStep").val('');
    $("#comment").val('');
    $("#modal-reason-add").css("display", "none");
    $("#modal-target-month").css("display", "none");
    $("#newTargetMonth").val('');
    $("#modal-submit-date").css("display", "none");
    $("#jobId-submit-date").val('');
});

function complainJob() {
    var jobId = $("#jd").val();
    var complain = $("#complain").val();
    var url = $url + 'job/detail/add-complain';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId, complain: complain },
        success: function(data) {
            if (data.status) {
                $("#complain-text").html(data.text);
                $("#modal-complain").css("display", "none");
            } else {
                alert("Complain text can not empty!")
            }

        }
    });
}
$("#cancel-complete").click(function() {
    $("#modal-reason").fadeIn(300);
    $("#completeTarget").prop("checked", false);
});
$("#cancel-sub-complete").click(function() {
    $("#modal-reason-add").fadeIn(300);
    $("#completeTarget").prop("checked", false)
});
$("#completeTarget").change(function() {
    if ($("#completeTarget").prop("checked") == false) {
        $("#send-approve").removeAttr("disabled");
    }
});

function showSubmitDate(jobId, jobCategoryId) {
    if ($("#submit-report").prop("checked") == true) {
        $("#modal-submit-date").fadeIn(300);
        $("#jobId-submit-date").val(jobId);
        $("#jobCatId-submit-date").val(jobCategoryId);

    }
}

function saveSubmitDate() {
    var jobId = $("#jobId-submit-date").val();
    var jobCategoryId = $("#jobCatId-submit-date").val();
    var submitDate = $("#submit-date").val();
    var url = $url + 'job/detail/save-submit-date';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId, jobCategoryId: jobCategoryId, submitDate: submitDate },
        success: function(data) {
            if (data.status) {
                $("#modal-submit-date").css("display", "none");
                $("#jobId-submit-date").val('');
                $("#jobCatId-submit-date").val('');
            } else {
                $("#modal-submit-date").css("display", "none");
                $("#jobId-submit-date").val('');
                $("#jobCatId-submit-date").val('');
                $("#submit-report").prop("checked") = false;
            }

        }
    });
}

function seeDocument(jobTypeId) {
    var url = $url + 'job/detail/job-type-document';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobTypeId: jobTypeId },
        success: function(data) {
            if (data.status) {
                $("#document-list").html(data.text);
                $("#modal-document").fadeIn(300);
            }

        }
    });
}

function editDocument(jobTypeId) {
    var url = $url + 'job/detail/edit-job-type-document';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobTypeId: jobTypeId },
        success: function(data) {
            if (data.status) {
                $("#edit-document").text(data.text);
                $("#modal-document").fadeIn(300);
            }

        }
    });
}

function cancelStepDue() {
    var url = $url + 'job/detail/cancel-complete';
    var jobStepId = $("#stepCancel").val();
    var reason = $("#reason").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId, reason: reason },
        success: function(data) {
            if (data.status) {
                //$("#detail-job-form").submit();
                location.reload();
            } else {
                alert("Reason text can not empty!")
            }

        }
    });
}

function cancelAdditionalStepDue() {
    var url = $url + 'job/detail/cancel-additional-step-complete';
    var additionalStepId = $("#additionalStepId").val();
    var reason = $("#reason-add").val();
    if (reason != '') {
        if (confirm('Are you sure to cancel this complete step?')) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { additionalStepId: additionalStepId, reason: reason },
                success: function(data) {
                    if (data.status) {
                        location.reload();
                    } else {
                        alert("Reason can not empty ! ! !")
                    }

                }
            });
        }
    } else {
        alert("Reason can not empty ! ! !");
    }
}

function cancelDetail(jobStepId) {
    $("#modal-cancel-detail").fadeIn(300);
    var url = $url + 'job/detail/cancel-detail';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId },
        success: function(data) {
            if (data.status) {
                $("#cancellationDetail").html(data.text);
            } else {
                $("#cancellationDetail").html('');
            }

        }
    });
}

function cancelSubDetail(additionalStepId) {
    $("#modal-cancel-detail").fadeIn(300);
    var url = $url + 'job/detail/cancel-sub-detail';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { additionalStepId: additionalStepId },
        success: function(data) {
            if (data.status) {
                $("#cancellationDetail").html(data.text);
            } else {
                $("#cancellationDetail").html('');
            }

        }
    });
}

function showCompleteJob(total, round) {
    var check = 0;
    if ($("#jobStep" + round).prop('checked') == true) {
        check = 1;
        /*if (($("#totalSubStep-" + round).length > 0)) { //ถ้ามี stepย่อย
            totalSubStep = $("#totalSubStep-" + round).val();
            s = 1;
            while (s <= totalSubStep) {
                $("#subJobStep-" + round + '-' + s).css("display", "");
                $("#subJobStep-" + round + '-' + s).prop('checked', true);
                s++;
            }
        }*/
        if (($("#totalSubStep-" + round).length > 0)) { //ถ้ามี stepย่อย
            totalSubStep = $("#totalSubStep-" + round).val();
            s = 1;
            // while (s <= totalSubStep) {
            $("#subJobStep-" + round + '-' + s).css("display", "");
            //  $("#subJobStep-" + round + '-' + s).prop('checked', true);
            //   s++;
            // }
        }
        var fag = 1;
        if (($("#totalSubStep-" + round).length > 0)) { //ถ้ามี stepย่อย
            totalSubStep = $("#totalSubStep-" + round).val();
            s = 1;

            while (s <= totalSubStep) {
                //$("#subJobStep-" + round + '-' + s).css("display", "");
                if ($("#subJobStep-" + round + '-' + s).prop('checked') == false) {
                    fag = 0;
                }
                //$("#subJobStep-" + round + '-' + s).prop('checked', true);
                s++;
            }
        }
        if (fag == 1) {
            $("#jobStep" + parseInt(round + 1)).css("display", "");
        }

        /*if ($("#jobStep" + total).prop('checked') == true) {
            $("#completeTarget").prop("checked", true);
            $("#jobCateTargetNext").css("display", "");
        }*/
    } else {
        var i = 1;
        while (i <= total) {
            if (i > round) {
                $("#jobStep" + i).prop("checked", false);
                $("#jobStep" + i).css("display", "none");
                if ($("#jobStep" + total).prop('checked') == false) {
                    $("#completeTarget").prop("checked", false);
                    /*$("#jobCateTarget").css("display", "none");
                    $("#jobCateTargetNext").css("display", "none");*/
                }
                if (($("#totalSubStep-" + i).length > 0)) { //ถ้ามี step ย่อย
                    totalSubStep = $("#totalSubStep-" + i).val();
                    s = 1;
                    while (s <= totalSubStep) {
                        if (s >= 1) {
                            $("#subJobStep-" + i + '-' + s).css("display", "none");
                        }
                        $("#subJobStep-" + i + '-' + s).prop('checked', false);
                        s++;
                    }
                }
            }
            i++;
        }

        if (($("#totalSubStep-" + round).length > 0)) { //ถ้ามี step ย่อย
            totalSubStep = $("#totalSubStep-" + round).val();
            s = 1;
            while (s <= totalSubStep) {
                if (s > 1) {
                    $("#subJobStep-" + round + '-' + s).css("display", "none");
                }
                $("#subJobStep-" + round + '-' + s).prop('checked', false);
                s++;
            }
        }
        $("#subJobStep-" + round + '-1').prop("checked", false);
        $("#subJobStep-" + round + '-1').css("display", "none");

    }
    var jobStepId = $("#jobStep" + round).val();
    if (check == 1) {
        var url = $url + 'job/detail/show-next-target';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { jobStepId: jobStepId },
            success: function(data) {
                if (data.status) {
                    $("#jobCateTarget").css("display", "");
                    $("#completeTarget").prop("checked", true);
                    $("#jobCateTargetNext").css("display", "");
                } else {
                    $("#jobCateTarget").css("display", "none");
                    $("#jobCateTargetNext").css("display", "none");
                }
            }
        });
    } else {
        $("#jobCateTarget").css("display", "none");
        $("#completeTarget").prop("checked", false);
        $("#jobCateTargetNext").css("display", "none");
    }

    /*if (parseInt(total) == parseInt(round)) {
        if ($("#jobStep" + round).prop('checked') == true) {
            $("#jobCateTarget").css("display", "");
            $("#jobCateTargetNext").css("display", "");

        } else {
            $("#jobCateTarget").css("display", "none");
            $("#jobCateTargetNext").css("display", "none");
        }
    }*/
}

function showCompleteSubStep(round, total, index) {
    next = parseInt(index) + 1;

    if ($("#subJobStep-" + round + '-' + index).prop('checked') == true) {
        $("#subJobStep-" + round + '-' + next).css("display", "");
        var additionalStepId = $("#subJobStep-" + round + "-" + index).val();
        var url = $url + 'job/detail/show-next-target-add';
        var check = 1;

    } else {
        var check = 0;
        $("#subJobStep-" + round + '-' + next).css("display", "none");
        $("#subJobStep-" + round + '-' + next).prop('checked', false);
        /*if (($("#totalSubStep-" + round).length > 0)) { //ถ้ามี step ย่อย
            totalSubStep = $("#totalSubStep-" + round).val();
            s = 1;
            while (s <= totalSubStep) {
                if (s > 1) {
                    $("#subJobStep-" + round + '-' + s).css("display", "none");
                }
                $("#subJobStep-" + round + '-' + s).prop('checked', false);
                s++;
            }
        }*/
        /* $("#jobStep" + round).prop('checked', false);
         $("#jobStep" + (round + 1)).css("display", "none");*/
        var totalRound = $("#total-step").val();
        var a = round;
        var trueRound = round;
        while (round <= totalRound) {
            if (($("#totalSubStep-" + round).length > 0)) {
                s = 1;
                totalSubStep = $("#totalSubStep-" + round).val();
                while (s <= totalSubStep) {
                    if (s == index && round == trueRound) {
                        $("#subJobStep-" + trueRound + '-' + index).css("display", "");
                        $("#subJobStep-" + trueRound + '-' + index).prop('checked', false);
                    } else if (round <= totalRound) {
                        if (round != trueRound) {
                            $("#subJobStep-" + round + '-' + s).css("display", "none");
                            $("#subJobStep-" + round + '-' + s).prop('checked', false);
                        } else {
                            if (s > index) {
                                $("#subJobStep-" + round + '-' + s).css("display", "none");
                                $("#subJobStep-" + round + '-' + s).prop('checked', false);
                            }
                        }
                    }
                    s++;

                }

            }
            if (a != round) {
                $("#jobStep" + round).prop("checked", false);
                $("#jobStep" + round).css("display", "none");
            }
            a--;
            round++;
        }

    }
    if (total == index && $("#subJobStep-" + round + '-' + index).prop('checked') == true) {
        $("#jobStep" + round).prop('checked', true);
        $("#jobStep" + (round + 1)).css("display", "");
    }
    if (check == 1) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { additionalStepId: additionalStepId },
            success: function(data) {
                if (data.status == 1) {
                    $("#jobCateTarget").css("display", "");
                    $("#completeTarget").prop("checked", true);
                    $("#jobCateTargetNext").css("display", "");
                } else {
                    $("#jobCateTarget").css("display", "none");
                    $("#jobCateTargetNext").css("display", "none");
                }
            }
        });
    } else {
        $("#jobCateTarget").css("display", "none");
        $("#completeTarget").prop("checked", false);
        $("#jobCateTargetNext").css("display", "none");
    }


}

function deleteJob(jobId) {
    if (confirm('Are you suer to delete this job?')) {
        var url = $url + 'job/detail/delete-job';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { jobId: jobId },
            success: function(data) {
                if (data.status) {
                    window.location.reload();
                }
            }
        });
    }
}
$("#confirm-job-name").click(function() {
    var jobId = $("#jId").val();
    var jobName = $("#new-job-Name").val();
    var url = $url + 'job/detail/change-job-name';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId, jobName: jobName },
        success: function(data) {
            if (data.status) {
                $("#new-job-Name").val(jobName);
                $("#new-name").html(jobName);
                // $("#job-name").css("display", "flex");
                $("#job-name").show();
                $("#edit-job-name").css("display", "none");
            }
        }
    });

});
$("#change-job-name").click(function() {
    $("#job-name").css("display", "none");
    $("#edit-job-name").css("display", "flex");
});
$("#cancel-change-job-name").click(function() {
    $("#job-name").show();
    $("#edit-job-name").css("display", "none");
});
$("#change-job-category").click(function() {
    var isAlert = $("#isAlert").val();
    if (isAlert == 0) {
        if (confirm('Are you sure to change job category?')) {
            $("#job-category-zone").show();
            $("#isAlert").val(1);
        }
    } else {
        $("#job-category-zone").show();
    }

});
$("#cancel-change-job-category").click(function() {
    $("#job-category").val(null);
    $("#startMonth1").val('');
    $("#job-category-zone").css("display", "none");

});

function disableButton() {
    newTargetDate = $("#newTargetDate").val();
    $("#newTargetDate").attr("required", "required");
    var approver = $("#approver").val();
    if (newTargetDate == '' || $.trim(newTargetDate) == '') {
        alert('Target date cannot be null!!');
    } else {
        var fiscalYear = $("#fiscalYear").val();
        if (fiscalYear == '' || fiscalYear == null) {
            alert('Fiscal year cannot null');
        } else {
            if (approver == '' || approver == null) {
                alert('Please select Approver.');
            } else {
                // $("#send-approve").attr("disabled", true);
                // $("#detail-job-form").submit();
            }
        }
    }
}
$("#checkDate").change(function() {
    submitFilter();

});

function submitFilter() {
    var fiscalYear = $("#filter-fiscalYear").val();
    var month = $("#filter-month").val();
    var branchId = $("#filter-branch").val();
    var clientId = $("#filter-client").val();
    var fieldId = $("#filter-field").val();
    var categoryId = $("#filter-category").val();
    var teamId = $("#filter-team").val();

    var url = $url + 'job/job-summarize/search-summarize';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { fiscalYear: fiscalYear, month: month, branchId: branchId, fieldId: fieldId, categoryId: categoryId, clientId: clientId, teamId: teamId },
        success: function(data) {

        }
    });
    //$("#filter-job-type").submit();
}

function showStepHistory(jobStepId, index) {
    var url = $url + 'job/detail/log-duedate';
    var totalStep = $("#total-step").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId },
        success: function(data) {
            if (data.status) {
                $("#history-" + index).html(data.text);
            }
        }
    });
    i = 0;
    while (i <= totalStep) {
        if (i != index) {
            $("#history-" + i).hide();
        }
        i++;
    }
    $("#history-" + index).toggle();
}

function showAdjustHistory(jobStepId, index) {
    var url = $url + 'job/detail/log-adjust-date';
    var totalStep = $("#total-step").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobStepId: jobStepId },
        success: function(data) {
            if (data.status) {
                $("#history-adjust-" + jobStepId + '-' + index).html(data.text);
            }
        }
    });
    i = 0;
    while (i <= totalStep) {
        if (i != index) {
            $("#history-adjust" + jobStepId + i).hide();
        }
        i++;
    }
    $("#history-adjust-" + jobStepId + '-' + index).toggle();
}

function showAdjustAddHistory(additionalStepId) {
    var url = $url + 'job/detail/log-adjust-date-add';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { additionalStepId: additionalStepId },
        success: function(data) {
            if (data.status) {
                $("#history-adjust-add-" + additionalStepId).html(data.text);
            }
        }
    });
    $("#history-adjust-add-" + additionalStepId).toggle();
}

function additionalStep(stepId) {
    var url = $url + 'job/default/additional-step';
    var sort = $("#sort-" + stepId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { stepId: stepId, sort: sort },
        success: function(data) {
            if (data.status) {
                $("#sub-step-" + stepId).append(data.text);
                sort++;
                $("#sort-" + stepId).val(sort)
            }
        }
    });
}

function deleteAdditionalStep(stepId, id) {
    $("#add-" + stepId + "-" + id).html('');
    $("#add-" + stepId + "-" + id).hide;
}

function deleteOldAdditionalStep(additionalId, id) {
    if (confirm('Are you sure to delete this step from this job?')) {
        var url = $url + 'job/detail/delete-additional-step';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { additionalId: additionalId },
            success: function(data) {
                if (data.status) {
                    $("#add-" + additionalId + "-" + id).html('');
                    $("#add-" + additionalId + "-" + id).hide;
                }
            }
        });
    }
}

function fieldInGroup() {
    var groupId = $("#groupField").val();
    var branchId = $("#job-branch").val();
    var url = $url + 'job/detail/field-in-group';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { groupId: groupId, branchId, branchId },
        success: function(data) {
            if (data.status) {
                $("#all-field").html(data.text);
            } else {
                $("#all-field").html('');
            }
        }
    });
}

function additionalStepUpdate(stepId) {
    var url = $url + 'job/detail/add-more-additional-step';
    var sort = $("#sort-" + stepId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { stepId: stepId, sort: sort },
        success: function(data) {
            if (data.status) {
                $("#sub-step-" + stepId).append(data.text);
                sort++;
                $("#sort-" + stepId).val(sort)
            }
        }
    });
}

function changeJobType() {
    if (confirm('Change current target job type will not effect with last target. Are you sure to change job type?')) {
        var jobTypeId = $("#jobType").val();
        var jobId = $("#jId").val();
        var jobCategoryId = $("#jcId").val();
        var url = $url + 'job/detail/change-job-type';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { jobTypeId: jobTypeId, jobId: jobId, jobCategoryId: jobCategoryId },
            success: function(data) {
                window.location.reload();
            }
        });
    }
}

function exportIssue(jobCategoryId) {
    var url = $url + 'job/detail/export-issue';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobCategoryId: jobCategoryId },
        success: function(data) {

        }
    });
}

function showModalTarget(jobCategoryId) {
    $("#modal-target-date").fadeIn(300);
    $("#newTargetDate").val('');
    $("#jobCateIdTarget").val(jobCategoryId);
    var oldTargetDate = $("#targetDate" + jobCategoryId).val()
    $("#old-targetDate").text(oldTargetDate);
}

function changeTargetDate() {
    var jobCategoryId = $("#jobCateIdTarget").val();
    var newTargetDate = $("#newTargetDate").val();
    var url = $url + 'job/detail/change-target-date';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobCategoryId: jobCategoryId, newTargetDate: newTargetDate },
        success: function(data) {
            location.reload();
        }
    });
}



function showModalFiscal(jobCategoryId) {
    $("#modal-fiscal-year").fadeIn(300);
    $("#newFiscalYear").val('');
    $("#jobCateIdFiscal").val(jobCategoryId);
    var oldFiscalYear = $("#fiscalYear" + jobCategoryId).val()
    $("#old-fiscalYear-input").val(oldFiscalYear);
    $("#old-fiscalYear").text(oldFiscalYear);
}

function changeFiscalYear() {
    var jobCategoryId = $("#jobCateIdFiscal").val();
    var newFiscalYear = $("#newFiscalYear").val();
    var oldFiscalYear = $("#old-fiscalYear-input").val();
    var url = $url + 'job/detail/change-fiscal-year';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobCategoryId: jobCategoryId, newFiscalYear: newFiscalYear, oldFiscalYear: oldFiscalYear },
        success: function(data) {
            if (data.status) {
                location.reload();
            }
        }
    });
}

function showModalTargetMonth(jobCategoryId) {
    $("#modal-target-month").fadeIn(300);
    $("#newTargetMonth").val('');
    $("#jobCateIdTargetMonth").val(jobCategoryId);
    var oldTargetMonth = $("#targetMonthText" + jobCategoryId).val()
    $("#old-targetMonth").text(oldTargetMonth);
}

function changeTargetMonth() {
    var jobCategoryId = $("#jobCateIdTargetMonth").val();
    var newTargetMonth = $("#startMonth1").val();
    var url = $url + 'job/detail/change-target-month';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobCategoryId: jobCategoryId, newTargetMonth: newTargetMonth },
        success: function(data) {
            if (data.status) {
                location.reload();
            }
        }
    });
}

function showAdjustDate(stepId, jobcateId) {
    $("#adjustDate-" + stepId + '-' + jobcateId).toggle();
}

function saveAdjust(stepId, jobcateId) {
    var newDate = $("#adjustdate-" + stepId + '-' + jobcateId).val();
    if (confirm('Change complete date to ' + newDate)) {
        var url = $url + 'job/detail/change-complete-date';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { jobcateId: jobcateId, stepId: stepId, newDate: newDate },
            success: function(data) {
                if (data.status) {
                    $("#adjustDate-" + stepId + '-' + jobcateId).css("display", "none");
                    $("#completeDate-" + stepId + '-' + jobcateId).html(data.newDate);
                    location.reload();
                }
            }
        });
    }
}

function showAdjustAdditionalDate(additionalStepId) {
    $("#adjustAddtionalDate-" + additionalStepId).toggle();
}

function saveAdjustAdditional(additionalStepId) {
    var newDate = $("#adjustAdditionaldate-" + additionalStepId).val();
    if (confirm('Change complete date to ' + newDate)) {
        var url = $url + 'job/detail/change-complete-date-additional';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { additionalStepId: additionalStepId, newDate: newDate },
            success: function(data) {
                if (data.status) {
                    $("#adjustAddtionalDate-" + additionalStepId).css("display", "none");
                    $("#completeAdditionalDate-" + additionalStepId).html(data.newDate);
                    location.reload();
                }
            }
        });
    }
}

function addMoreClient(jobId) {
    var number = $("#number").val();
    var url = $url + 'job/clone/add-more-client';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobId: jobId, number: number },
        success: function(data) {
            if (data.status) {
                $("#otherClient").append(data.textMore);
                newNumber = parseInt(number) + 1;
                $("#number").val(newNumber);
            }
        }
    });

}

function closeAddmore(number) {
    $("#add-more-" + number).hide();
    $("#add-more-" + number).html('');
}