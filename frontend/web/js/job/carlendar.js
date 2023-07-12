var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

$("#previous-year").click(function() {
    var currentYear = $("#year").val();
    var month = $("#month").val();
    var year = parseInt(currentYear) - 1;
    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var url = $url + 'job/carlendar/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            /* if (data.status) {
                 $("#result-date").html(data.target);
                 $("#year").val(year);
                 $("#month").val(month);
                 $("#current-year").html(year);
                 $("#current-date").html(data.selectDate);
             }*/
        }
    });
});
$("#next-year").click(function() {
    var currentYear = $("#year").val();
    var month = $("#month").val();
    var year = parseInt(currentYear) + 1;
    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var url = $url + 'job/carlendar/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            /*if (data.status) {
                $("#result-date").html(data.target);
                $("#year").val(year);
                $("#month").val(month);
                $("#current-year").html(year);
                $("#current-date").html(data.selectDate);
            }*/
        }
    });
});

$("#previous-month").click(function() {
    var year = $("#year").val();
    var currrentMonth = $("#month").val();
    var month = parseInt(currrentMonth) - 1;
    if (month == 0) {
        month = 12;
        year = parseInt(year) - 1;
    }
    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var url = $url + 'job/carlendar/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            /* if (data.status) {
                 $("#result-date").html(data.target);
                 $("#year").val(year);
                 $("#month").val(month);
                 $("#current-year").html(year);
                 $("#current-date").html(data.selectDate);
             }*/
        }
    });
});
$("#next-month").click(function() {
    var year = $("#year").val();
    var currrentMonth = $("#month").val();
    var month = parseInt(currrentMonth) + 1;
    if (month == 13) {
        month = 1;
        year = parseInt(year) + 1;
    }
    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var url = $url + 'job/carlendar/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            /* if (data.status) {
                 $("#result-date").html(data.target);
                 $("#year").val(year);
                 $("#month").val(month);
                 $("#current-year").html(year);
                 $("#current-date").html(data.selectDate);
             }*/
        }
    });
});
$("#branch-search-carlendar").change(function() {
    var id = $("#branch-search-carlendar").val();
    filterJobCarlendar();
});

function filterJobCarlendar() {
    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var currentYear = $("#year").val();
    var currentMonth = $("#month").val();
    var year = parseInt(currentYear);
    var month = parseInt(currentMonth);
    var url = $url + 'job/carlendar/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            if (data.status) {
                // $("#result-date").html(data.target);
                // $("#year").val(year);
                // $("#month").val(month);
                // $("#current-year").html(year);
                // $("#current-date").html(data.selectDate);
            }
        }
    });
}

function checkcheck() {
    if ($("#step-due-check").is(':checked')) {
        $("#step-due-check").val(1);
    } else {
        $("#step-due-check").val(0);
    }
    if ($("#final-due-check").is(':checked')) {
        $("#final-due-check").val(1);
    } else {
        $("#final-due-check").val(0);
    }
    filterJobCarlendar();
}

function showJobDate(jobDate) {

    var branchId = $("#branch-search-carlendar").val();
    var fieldId = $("#field-job").val();
    var categoryId = $("#category-search-job").val();
    var teamId = $("#team-job").val();
    var personId = $("#user-type-search-employee").val();
    var stepCheck = $("#step-due-check").val();
    var finalCheck = $("#final-due-check").val();
    var status = $("#status-search").val();
    var currentYear = $("#year").val();
    var currentMonth = $("#month").val();
    var year = parseInt(currentYear);
    var month = parseInt(currentMonth);
    var url = $url + 'job/carlendar/search-job-date';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { jobDate: jobDate, branchId: branchId, fieldId: fieldId, categoryId: categoryId, teamId: teamId, personId: personId, year: year, month: month, stepCheck: stepCheck, finalCheck: finalCheck, status: status },
        success: function(data) {
            /*if (data.status) {
                $("#select-date-job").html(data.text);
                $("#date-select").html(data.date);
                $("#modal-job").fadeIn(300);
                // $("#result-date").html(data.target);
                // $("#year").val(year);
                // $("#month").val(month);
                // $("#current-year").html(year);
                // $("#current-date").html(data.selectDate);
            } else {

            }*/
        }
    });
}
$("#next-year-sales").click(function() {
    var currentYear = $("#year").val();
    var month = $("#month").val();
    var year = parseInt(currentYear) + 1;
    var branchId = $("#branch-sale").val();
    var timezone = $("#timezone").val();
    var salesActivity = 0;
    var existingMeeting = 0;
    var internalMeeting = 0;
    var other = 0;
    if ($("#sales-activity").prop("checked") == true) {
        salesActivity = 1;
    }
    if ($("#existing-meeting").prop("checked") == true) {
        existingMeeting = 1;
    }
    if ($("#internal-meeting").prop("checked") == true) {
        internalMeeting = 1;
    }
    if ($("#other").prop("checked") == true) {
        other = 1;
    }
    var url = $url + 'sales/default/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, year: year, month: month, timezone: timezone, salesActivity: salesActivity, existingMeeting: existingMeeting, internalMeeting: internalMeeting, other: other },
        success: function(data) {

        }
    });
});
$("#previous-year-sales").click(function() {
    var currentYear = $("#year").val();
    var month = $("#month").val();
    var year = parseInt(currentYear) - 1;
    var branchId = $("#branch-sale").val();
    var timezone = $("#timezone").val();
    var salesActivity = 0;
    var existingMeeting = 0;
    var internalMeeting = 0;
    var other = 0;
    if ($("#sales-activity").prop("checked") == true) {
        salesActivity = 1;
    }
    if ($("#existing-meeting").prop("checked") == true) {
        existingMeeting = 1;
    }
    if ($("#internal-meeting").prop("checked") == true) {
        internalMeeting = 1;
    }
    if ($("#other").prop("checked") == true) {
        other = 1;
    }
    var url = $url + 'sales/default/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, year: year, month: month, timezone: timezone, salesActivity: salesActivity, existingMeeting: existingMeeting, internalMeeting: internalMeeting, other: other },
        success: function(data) {

        }
    });
});
$("#previous-month-sales").click(function() {
    var year = $("#year").val();
    var currrentMonth = $("#month").val();
    var month = parseInt(currrentMonth) - 1;
    if (month == 0) {
        month = 12;
        year = parseInt(year) - 1;
    }
    var branchId = $("#branch-sale").val();
    var timezone = $("#timezone").val();
    var salesActivity = 0;
    var existingMeeting = 0;
    var internalMeeting = 0;
    var other = 0;
    if ($("#sales-activity").prop("checked") == true) {
        salesActivity = 1;
    }
    if ($("#existing-meeting").prop("checked") == true) {
        existingMeeting = 1;
    }
    if ($("#internal-meeting").prop("checked") == true) {
        internalMeeting = 1;
    }
    if ($("#other").prop("checked") == true) {
        other = 1;
    }
    var url = $url + 'sales/default/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, year: year, month: month, timezone: timezone, salesActivity: salesActivity, existingMeeting: existingMeeting, internalMeeting: internalMeeting, other: other },
        success: function(data) {

        }
    });
});
$("#next-month-sales").click(function() {
    var year = $("#year").val();
    var currrentMonth = $("#month").val();
    var month = parseInt(currrentMonth) + 1;
    if (month == 13) {
        month = 1;
        year = parseInt(year) + 1;
    }
    var branchId = $("#branch-sale").val();
    var timezone = $("#timezone").val();
    var salesActivity = 0;
    var existingMeeting = 0;
    var internalMeeting = 0;
    var other = 0;
    if ($("#sales-activity").prop("checked") == true) {
        salesActivity = 1;
    }
    if ($("#existing-meeting").prop("checked") == true) {
        existingMeeting = 1;
    }
    if ($("#internal-meeting").prop("checked") == true) {
        internalMeeting = 1;
    }
    if ($("#other").prop("checked") == true) {
        other = 1;
    }

    var url = $url + 'sales/default/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, year: year, month: month, timezone: timezone, salesActivity: salesActivity, existingMeeting: existingMeeting, internalMeeting: internalMeeting, other: other },
        success: function(data) {

        }
    });
});

function carlendarFilter() {
    var year = $("#year").val();
    var month = $("#month").val();
    var salesActivity = 0;
    var existingMeeting = 0;
    var internalMeeting = 0;
    var other = 0;
    var branchId = $("#branch-sale").val();
    var timezone = $("#timezone").val();
    if ($("#sales-activity").prop("checked") == true) {
        salesActivity = 1;
    }
    if ($("#existing-meeting").prop("checked") == true) {
        existingMeeting = 1;
    }
    if ($("#internal-meeting").prop("checked") == true) {
        internalMeeting = 1;
    }
    if ($("#other").prop("checked") == true) {
        other = 1;
    }
    var url = $url + 'sales/default/search-job-carlendar';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, year: year, month: month, timezone: timezone, salesActivity: salesActivity, existingMeeting: existingMeeting, internalMeeting: internalMeeting, other: other },
        success: function(data) {

        }
    });
}

function showAddSchedule(year, month, day) {
    $("#modal-add-schedule").fadeIn(300);
    $("#year-sale").val(year);
    $("#month-sale").val(month);
    $("#select-day-kpi").val(day);
    $("#select-month-kpi").val(month);
    $("#select-year-kpi").val(year);
    $("#day-sale").val(day);
    var url = $url + 'sales/default/calendar-text';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { year: year, month: month, day: day },
        success: function(data) {
            $("#date-text").html(data.dateText);
        }
    });
}
$(".close-modal-schedule").click(function() {
    $(".modal-schedule").css("display", "none");
});