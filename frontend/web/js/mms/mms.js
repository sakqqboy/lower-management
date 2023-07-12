var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function filterAnalysisJob() {
    const year = [$("#filterYear0").val(), $("#filterYear1").val(), $("#filterYear2").val()];
    const month = [$("#filterMonth0").val(), $("#filterMonth1").val(), $("#filterMonth2").val()];
    var jobTypeId = $("#jobTypeAnalysis").val();
    var branchId = $("#branchAnalysis").val();
    var teamId = $("#teamAnalysis").val();
    var personId = $("#personAnalysis").val();
    var stepId = $("#stepAnalysis").val();
    var url = $url + 'mms/analysis/filter-analysis';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, branchId: branchId, jobTypeId: jobTypeId, teamId: teamId, personId: personId, stepId: stepId },
        success: function(data) {}
    });
}

function filterYearlyAnalysisJob() {
    const year = [$("#filterYearlyYear0").val(), $("#filterYearlyYear1").val(), $("#filterYearlyYear2").val()];
    const month = [$("#filterYearlyMonth0").val(), $("#filterYearlyMonth1").val(), $("#filterYearlyMonth2").val()];
    var jobTypeId = $("#jobTypeYearlyAnalysis").val();
    var branchId = $("#branchYearlyAnalysis").val();
    var teamId = $("#teamYearlyAnalysis").val();
    var personId = $("#personYearlyAnalysis").val();
    var stepId = $("#stepYearlyAnalysis").val();
    var url = $url + 'mms/analysis/filter-yearly-analysis';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, branchId: branchId, jobTypeId: jobTypeId, month: month, teamId: teamId, personId: personId, stepId: stepId },
        success: function(data) {}
    });
}
$("#analysis-type").on('change', function() {
    var url = $("#analysis-type").val();
    window.location.href = url;
});

function filterJobTypeAnalysis() {
    var jobTypeId = $("#jobTypeAnalysis").val();
    var branchId = $("#branchJobTypeyAnalysis").val();
    var teamId = $("#teamJobTypeAnalysis").val();
    var personId = $("#personJobTypAnalysis").val();
    var month = $("#monthJobTypAnalysis").val();
    var year = $("#yearJobTypAnalysis").val();
    var url = $url + 'mms/analysis/filter-job-type-analysis';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, jobTypeId: jobTypeId, teamId: teamId, personId: personId, month: month, year: year },
        success: function(data) {}
    });
}