var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

$("#jobtype-branch").change(function() {
    var branchId = $("#jobtype-branch").val();
    var url = $url + 'job/job-type-calendar/find-job-type';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#jobTypeBranch").html(data.textJobType);
                $("#teamBranch").html(data.teamBranch);
            }
        }
    });
});

function addCompareValue(compareValue) {
    // alert(compareValue);
    $("#compareTarget").val(parseInt(compareValue));
}

function filterJobTypeCalendar() {
    var branchId = $("#jobtype-branch").val();
    var jobTypeId = $("#jobTypeBranch").val();
    var teamId = $("#teamBranch").val();
    if (branchId == '' || jobTypeId == '') {
        alert('Please select Branch & Job type');
    } else {
        var compare = $("#compareTarget").val();
        var url = $url + 'job/job-type-calendar/client-job-type';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { branchId: branchId, jobTypeId: jobTypeId, teamId: teamId, compare: compare },
            success: function(data) {
                if (data.status) {
                    $("#jobtype-result").html('');
                    $("#jobtype-result").html(data.textResult);
                } else {
                    $("#jobtype-result").html('Not Found ! ! !');
                }
            }
        });
    }
}