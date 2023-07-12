var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updatePosition(positionId) {
    var url = $url + 'setting/structure/update-position';
    var positionName = $("#positionNameInput" + positionId).val();
    var detail = $("#positionDetailInput" + positionId).val();
    var branchId = $("#branchInput" + positionId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { positionId: positionId, positionName: positionName, detail, detail, branchId: branchId },
        success: function(data) {
            if (data.status) {
                // $("#tr-edit" + positionId).hide();
                // $("#positionName" + positionId).html(data.positionName);
                // $("#positionDetail" + positionId).html(data.detail);
                // $("#positionLevel" + positionId).html(data.level);
                window.location.reload();

            }
        }
    });
}

function disablePosition(positionId) {
    var url = $url + 'setting/structure/disable-position';
    if (confirm('Becareful, if position is deleted, Employee, will also be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { positionId: positionId },
            success: function(data) {
                if (data.status) {
                    // $("#position" + positionId).hide();
                    window.location.reload();
                }
            }
        });
    }
}

function filterPosition() {
    var url = $url + 'setting/structure/search-position';
    var branchId = $("#branchSearchPosition").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#position-search").html(data.text);
            }
        }
    });
}