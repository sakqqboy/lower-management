var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateBranch(branchId) {
    var url = $url + 'setting/structure/update-branch';
    var branchName = $("#branchNameInput" + branchId).val();
    var countryId = $("#countryIdInput" + branchId).val();
    var countryId = $("#countryId" + branchId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, branchName: branchName, countryId, countryId },
        success: function(data) {
            if (data.status) {
                $("#tr-edit" + branchId).hide();
                $("#branchName" + branchId).html(data.branchName);
                $("#countryName" + branchId).html(data.countryName);
                $("#countryId" + branchId).val(data.countryId);
            }
        }
    });
}

function disableBranch(branchId) {
    var url = $url + 'setting/structure/disable-branch';
    if (confirm('Becareful, if branch is deleted, Section, Position, team, employee, job will also be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { branchId: branchId },
            success: function(data) {
                if (data.status) {
                    $("#branch" + branchId).hide();
                }
            }
        });
    }
}