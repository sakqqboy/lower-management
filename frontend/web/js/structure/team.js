var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateTeam(teamId) {
    var url = $url + 'setting/structure/update-team';
    var teamName = $("#teamNameInput" + teamId).val();
    var detail = $("#teamDetailInput" + teamId).val();
    var sectionId = $("#sectionTeamInput" + teamId).val();
    var branchId = $("#branchTeamInput" + teamId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { sectionId: sectionId, teamName: teamName, detail: detail, teamId: teamId, branchId: branchId },
        success: function(data) {
            if (data.status) {
                /*$("#tr-edit" + teamId).hide();
                $("#teamName" + teamId).html(data.teamName);
                $("#teamDetail" + teamId).html(data.teamDetail);
                $("#teamSection" + teamId).html(data.sectionName);*/
                window.location.reload();
            }
        }
    });
}

function branchSectionTeam() {
    var url = $url + 'setting/structure/search-section-team';
    var branchId = $("#branchSearchSectionTeam").val();
    if (branchId != '') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { branchId: branchId },
            success: function(data) {
                if (data.status) {
                    $("#sectionTeam").removeAttr("disabled");
                    $("#sectionTeam").html(data.text);
                }
            }
        });
    } else {
        $("#sectionTeam").attr("disabled", "disabled");
        $("#sectionTeam").html("<option value=''>Section</option>");
    }
}

function branchSectionEdit(teamId) {
    var url = $url + 'setting/structure/search-section-team';
    var branchId = $("#branchTeamInput" + teamId).val();
    if (branchId != '' && branchId != 0 && branchId != null) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { branchId: branchId },
            success: function(data) {
                if (data.status) {
                    $("#sectionTeamInput" + teamId).removeAttr("disabled");
                    $("#sectionTeamInput" + teamId).html(data.text);
                }
            }
        });
    } else {
        $("#sectionTeamInput" + teamId).attr("disabled", "disabled");
        $("#sectionTeamInput" + teamId).html("<option value=''>Section</option>");
    }
}

function filterTeam() {
    var url = $url + 'setting/structure/filter-search-section-team';
    var branchId = $("#branchFilterSection").val();
    var sectionId = $("#sectionFilterTeam").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, sectionId: sectionId },
        success: function(data) {
            if (data.status) {
                $("#sectionFilterTeam").html(data.text);
                $("#team-search").html(data.textRender);
            }
        }
    });
}

function disableTeam(teamId) {
    var url = $url + 'setting/structure/disable-team';
    if (confirm('Becareful, if team is deleted, Employee changed to none team, job will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { teamId: teamId },
            success: function(data) {
                if (data.status) {
                    window.location.reload();
                }
            }
        });
    }
}