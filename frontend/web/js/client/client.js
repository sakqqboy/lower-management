var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function selectYearJob() {
    var clientId = $("#clientId").val();
    var branchId = $("#branch-client").val();
    var sort = $("#sort-client").val();
    var yearOnProcess = $("#client-select-year").val();
    var yearComplete = $("#client-select-year-complete").val();
    var url = $url + 'client/default/search-job-branch';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, sort: sort, yearOnProcess: yearOnProcess, yearComplete: yearComplete, clientId: clientId },
        success: function(data) {
            if (data.status) {}
        }
    });
    /*$.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { clientId: clientId, year: year },
        success: function(data) {
            if (data.status) {
                if (data.textJob != "") {
                    $("#job-onprocess").html(data.textJob);
                    $("#client-amount").html(data.amount);
                } else {
                    $("#job-onprocess").html("<div class='col-12 mt-20 font-size16 text-center'>No data</div>");
                    $("#client-amount").html(data.amount);
                }
            }
        }
    });*/
}

/*function selectYearJobComplete() {
    var clientId = $("#clientId").val();
    var year = $("#client-select-year-complete").val();
    var url = $url + 'client/default/client-select-year-complete';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { clientId: clientId, year: year },
        success: function(data) {
            if (data.status) {
                if (data.textComplete != "") {
                    $("#job-complete").html('');
                    $("#job-complete").html(data.textComplete);
                    $("#client-amount-complete").html(data.amount);
                } else {
                    $("#job-complete").html("<div class='col-12 mt-20 font-size16 text-center'>No data</div>");
                    $("#client-amount-complete").html(data.amount);
                }
            }
        }
    });
}*/

function disableClient(clientId) {
    var url = $url + 'client/default/disable-client';
    if (confirm('Becareful, if Client is deleted, job will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { clientId: clientId },
            success: function(data) {
                if (data.status) {
                    $("#client" + clientId).hide();
                }
            }
        });
    }
}

function searchClient() {
    var sort = $("#sort-client").val();
    var branchId = $("#branch-client").val();
    var url = $url + 'client/default/client-branch';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, sort: sort },
        success: function(data) {
            if (data.status) {
                $("#client-search").html(data.text);
                $("#client-job").html('');
                if (data.textJob != "") {
                    $("#client-job").html(data.textJob);
                }
            } else {
                $("#client-search").html('');
                $("#client-job").html('');
            }
        }
    });
}

function searchFilterJob() {
    var branchId = $("#branch-client").val();
    var sort = $("#sort-client").val();
    var yearOnProcess = $("#client-select-year").val();
    var yearComplete = $("#client-select-year-complete").val();
    var url = $url + 'client/default/search-job-branch';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, sort: sort, yearOnProcess: yearOnProcess, yearComplete: yearComplete },
        success: function(data) {
            if (data.status) {}
        }
    });
}

function clientJob(clientId) {
    var url = $url + 'client/default/search-job-branch';
    var branchId = $("#branch-client").val();
    var sort = $("#sort-client").val();
    var yearOnProcess = $("#client-select-year").val();
    var yearComplete = $("#client-select-year-complete").val();
    var totalCli = $("#total-client").val()
    var i = 1;
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { clientId: clientId, branchId: branchId, sort: sort, yearOnProcess: yearOnProcess, yearComplete: yearComplete },
        success: function(data) {}
    });
}

function filterClient() {
    var branchId = $("#branch-client").val();
    var searchName = $("#search-client").val();
    var url = $url + 'client/default/search-client';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, searchName: searchName },
        success: function(data) {
            $("#client-result").html(data.text);
        }
    });
}

function showRemark() {
    $("#modal-remark-client").fadeIn(300);
    var remark = $("#show-remark-client").text();
    $("#remark-client").val(remark);
}

function saveClientRemark() {
    var cleintId = $("#clientId").val();
    var remark = $("#remark-client").val();
    var url = $url + 'client/default/save-remark';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { cleintId: cleintId, remark: remark },
        success: function(data) {
            $("#show-remark-client").html(remark);
            $("#modal-remark-client").css("display", "none");
            $("#remark-client").html('');
        }
    });
}
$(".close-modal").click(function() {
    $("#modal-remark-client").css("display", "none");
});