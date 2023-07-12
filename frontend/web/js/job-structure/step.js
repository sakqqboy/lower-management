var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateJobStep(stepId) {
    var url = $url + 'setting/job-structure/update-step';
    var stepName = $("#stepNameInput" + stepId).val();
    var jobTypeId = $("#jobTypeInput" + stepId).val();
    var sort = $("#sortInput" + stepId).val();
    if ($.trim(stepName) != '' && $.trim(sort) != '') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { stepId: stepId, stepName: stepName, jobTypeId: jobTypeId, sort: sort },
            success: function(data) {
                if (data.status) {
                    window.location.reload();
                }
            }
        });
    } else {
        alert('Please input Name and sort');
    }
}


function disableStep(stepId) {
    var url = $url + 'setting/job-structure/disable-step';
    if (confirm('Becareful, if step is deleted, job step will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { stepId: stepId },
            success: function(data) {
                if (data.status) {
                    $("#step" + stepId).hide();
                }
            }
        });
    }
}
$("#branchSearchType").change(function() {
    var url = $url + 'setting/job-structure/job-branch';
    var branchId = $("#branchSearchType").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#jobType").removeAttr('disabled');
                $("#jobType").html(data.text);
                $("#stepName").removeAttr('disabled');
                $("#sort").removeAttr('disabled');
                $("#create-step").removeAttr('disabled');
                $("#add-step").show();
            } else {
                $("#jobType").prop('disabled', 'disabled');
                $("#stepName").prop('disabled', 'disabled');
                $("#sort").prop('disabled', 'disabled');
                $("#create-step").prop('disabled', 'disabled');
                $("#add-step").css("display", "none");
            }
        }
    });
});

function filterStep() {
    var branchId = $("#branchSearchStep").val();
    var jobTypeId = $("#jobTypeSearchStep").val();
    var url = $url + 'setting/job-structure/search-job-step';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, jobTypeId: jobTypeId },
        success: function(data) {
            if (data.status) {
                // $("#step-search").html(data.text);
                // $("#jobTypeSearchStep").html(data.jobTypeText);
            }
        }
    });
}

function searchEachType(stepId) {
    var url = $url + 'setting/job-structure/job-branch';
    var branchId = $("#branchSearchType" + stepId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#jobTypeInput" + stepId).html(data.text);
            }
        }
    });
}
$("#add-step").click(function() {
    $("#add-more-step").append("<div class='col-10'><input type = 'text' name = 'stepName' id = 'stepName' class = 'form-control mt-10' placeholder = 'Step Name' required></div><div class='col-2'> </div>");
    $("#add-more-sort").append("<input type='text' id='sort' name='sort' class='form-control mt-10' placeholder='Sort' required onKeyUp='if(isNaN(this.value)){this.value='';}'>")
});
$("#create-step").click(function() {
    var url = $url + 'setting/job-structure/create-step';
    var jobTypeId = $("#jobType").val();
    var stepName = [];
    var sort = [];
    $.each($("input[name='stepName']"), function() {
        stepName.push($(this).val());
    });
    $.each($("input[name='sort']"), function() {
        sort.push($(this).val());
    });
    if (stepName != '') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { stepName: stepName, sort: sort, jobTypeId: jobTypeId },
            success: function(data) {
                if (data.status) {
                    window.location.reload();
                } else {
                    alert("Something went wrong, please contact administrator.");
                }
            }
        });
    } else {
        return false;
    }
});