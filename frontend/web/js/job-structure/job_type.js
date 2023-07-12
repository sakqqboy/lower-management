var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateJobType(jobTypeId) {
    var url = $url + 'setting/job-structure/update-job-type';
    var jobTypeName = $("#jobTypeNameInput" + jobTypeId).val();
    var jobTypeDetail = $("#jobTypeDetailInput" + jobTypeId).val();
    var branchId = $("#branchInput" + jobTypeId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobTypeId: jobTypeId, jobTypeName: jobTypeName, jobTypeDetail: jobTypeDetail, branchId: branchId },
        success: function(data) {
            if (data.status) {
                window.location.reload();
            }
        }
    });
}


function disableJobType(jobTypeId) {
    var url = $url + 'setting/job-structure/disable-job-type';
    if (confirm('Becareful, if Job type is deleted, job, job type step will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { jobTypeId: jobTypeId },
            success: function(data) {
                if (data.status) {
                    $("#jobType" + jobTypeId).hide();
                }
            }
        });
    }
}

function filterJobType() {
    var url = $url + 'setting/job-structure/search-job-type';
    var branchId = $("#branchSearchJobType").val();
    var jobTypeId = $("#searchJobType").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, jobTypeId: jobTypeId },
        success: function(data) {
            if (data.status) {
                // $("#searchJobType").html(data.jobType);
                // $("#searchJobType").val(jobTypeId);
                // $("#job-type-search").html(data.text);
            }
        }
    });
}

function updateJobTypeDocument(jobTypeId) {
    var url = $url + 'setting/job-structure/update-job-type-document';
    var text = $("#edit-document").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobTypeId: jobTypeId, text: text },
        success: function(data) {
            if (data.status) {
                $("#modal-document").css("display", "none");
            }
        }
    });
}