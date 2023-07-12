var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;
$("#branch-kgi").change(function() {
    var url = $url + 'kpi/default/team-branch';
    var branchId = $("#branch-kgi").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            $("#dreamTeam-kgi").html(data.textTeam);
        }
    });

});

$("#branch-kgi-search").change(function() {
    var url = $url + 'kpi/default/team-branch';
    var branchId = $("#branch-kgi-search").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            $("#dreamTeam-kgi").html(data.textTeam);
            filterKgi();
        }
    });

});

function filterKgi() {
    var url = $url + 'kpi/default/filter-kgi';
    var branchId = $("#branch-kgi-search").val();
    var teamId = $("#dreamTeam-kgi").val();
    var teamPositionId = $("#teamPosition").val();
    var kgiGroupId = $("#kgi-group").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, teamId, teamId, teamPositionId: teamPositionId, kgiGroupId: kgiGroupId },
        success: function(data) {
            $("#kgi-search-result").html(data.textResult);
        }
    });
}

function disableKgi(kgiId) {
    var url = $url + 'kpi/default/delete-kgi';
    if (confirm('Are you sure to delete this KGI?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { kgiId: kgiId },
            success: function(data) {
                $("#kgi-" + kgiId).hide();
            }
        });
    }
}

function disableKpi(kpiId) {
    var url = $url + 'kpi/default/delete-kpi';
    if (confirm('Are you sure to delete this KPI?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { kpiId: kpiId },
            success: function(data) {
                $("#kpi-" + kpiId).hide();
            }
        });
    }
}

function disableKgiGroup(kgiGroupId) {
    var url = $url + 'kpi/default/delete-kpi-group';
    if (confirm('Are you sure to delete this KPI Group?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { kgiGroupId: kgiGroupId },
            success: function(data) {
                $("#kgi-group-" + kgiGroupId).hide();
            }
        });
    }
}
$("#next-year-kpi").click(function() {
    var currentYear = $("#yearKpi").val();
    var month = $("#monthKpi").val();
    var kpiId = $("#kpi").val();
    var pkpiId = $("#pkpi").val();
    var year = parseInt(currentYear) + 1;
    var url = $url + 'kpi/update/search-kpi-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, kpiId: kpiId, pkpiId: pkpiId },
        success: function(data) {

        }
    });
});
$("#previous-year-kpi").click(function() {
    var currentYear = $("#yearKpi").val();
    var month = $("#monthKpi").val();
    var kpiId = $("#kpi").val();
    var pkpiId = $("#pkpi").val();
    var year = parseInt(currentYear) - 1;
    var url = $url + 'kpi/update/search-kpi-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, kpiId: kpiId, pkpiId: pkpiId },
        success: function(data) {

        }
    });
});
$("#previous-month-kpi").click(function() {
    var year = $("#yearKpi").val();
    var currrentMonth = $("#monthKpi").val();
    var kpiId = $("#kpi").val();
    var pkpiId = $("#pkpi").val();
    // alert(currrentMonth);
    var month = parseInt(currrentMonth) - 1;
    // alert(month);
    if (month == 0) {
        month = 12;
        year = parseInt(year) - 1;
    }
    var url = $url + 'kpi/update/search-kpi-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, kpiId: kpiId, pkpiId: pkpiId },
        success: function(data) {

        }
    });
});
$("#next-month-kpi").click(function() {
    var year = $("#yearKpi").val();
    var currrentMonth = $("#monthKpi").val();
    var kpiId = $("#kpi").val();
    var pkpiId = $("#pkpi").val();
    var month = parseInt(currrentMonth) + 1;
    if (month == 13) {
        month = 1;
        year = parseInt(year) + 1;
    }
    var url = $url + 'kpi/update/search-kpi-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, kpiId: kpiId, pkpiId: pkpiId },
        success: function(data) {

        }
    });
});

function showProgressDetail(year, month, day, pkpiDetailId) {
    $("#modal-progress").fadeIn(300);
    $(".modal-schedule").css("display", "none");
    var url = $url + 'kpi/update/progress-detail';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, day: day, pkpiDetailId: pkpiDetailId },
        success: function(data) {
            $("#amount").val(data.amount);
            $("#progress_detail").val(data.detail);
            if (data.file != null) {
                $("#link").html(data.link);
            }
            $("#personalKpiDetailId").val(pkpiDetailId);
        }
    });

}
$(".close-modal-schedule").click(function() {
    $(".modal-progress").css("display", "none");
});

function filterEmployeeKpi() {
    var branchId = $("#branch-search-employee-kpi").val();
    var sectionId = $("#section-search-employee-kpi").val();
    var positionId = $("#position-search-employee-kpi").val();
    var teamId = $("#team-search-employee-kpi").val();
    var teamPositionId = $("#team-position-search-employee-kpi").val();
    var userTypeId = $("#user-type-search-employee-kpi").val();
    var url = $url + 'kpi/employee-kpi/search-employee';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, sectionId: sectionId, positionId: positionId, teamId: teamId, teamPositionId: teamPositionId, userTypeId: userTypeId },
        success: function(data) {

        }
    });
}

function saveUpdatePersonalKpi(pkpiId) {

    var url = $url + 'kpi/employee-kpi/update-personal-kpi';
    var amount = $("#personalKpi-" + pkpiId).val();
    $("#check-" + pkpiId).css("display", "none");
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { pkpiId: pkpiId, amount: amount },
        success: function(data) {
            $("#check-" + pkpiId).show();
        }
    })
}