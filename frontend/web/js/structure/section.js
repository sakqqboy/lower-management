var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateSection(sectionId) {
    var url = $url + 'setting/structure/update-section';
    var sectionName = $("#sectionNameInput" + sectionId).val();
    //var detail = $("#sectionDetailInput" + sectionId).val();
    var branchId = $("#branchInput" + sectionId).val();
    // var position = $("#position-input" + sectionId).val();
    var position = [];
    $.each($("input[name='position" + sectionId + "']:checked"), function() {
        position.push($(this).val());
    });
    // var position = $("input[name='position" + sectionId + "']").val();
    // alert(position);
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { sectionId: sectionId, sectionName: sectionName, branchId: branchId, position: position },
        success: function(data) {
            if (data.status) {
                // $("#tr-edit" + sectionId).hide();
                // $("#sectionName" + sectionId).html(data.sectionName);
                // $("#sectionDetail" + sectionId).html(data.sectionDetail);
                // $("#sectionPosition" + sectionId).html(data.positionText);
                window.location.reload();
            }
        }
    });
}

function disableSection(sectionId) {
    var url = $url + 'setting/structure/disable-section';
    if (confirm('Becareful, if section is deleted, Employee, team will also be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { sectionId: sectionId },
            success: function(data) {
                if (data.status) {
                    $("#section" + sectionId).hide();
                }
            }
        });
    }
}

function filterSection() {
    var url = $url + 'setting/structure/search-section';
    var branchId = $("#branchSearchSection").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#section-search").html(data.text);
            }
        }
    });
}

function sectionPosition() {
    var branchId = $("#branch-section").val();
    var url = $url + 'setting/structure/branch-position';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status || data.countSelect > 0) {
                $.each(data.select, function(key, val) {
                    $("#position" + val).css("display", "");
                });
                $.each(data.unSelect, function(key, val) {
                    $("#position" + val).css("display", "none");
                    $("#position-input" + val).prop("checked", false);
                });
            } else {
                $.each(data.allPosition, function(key, val) {
                    $("#position" + val).css("display", "none");
                    $("#position-input" + val).prop("checked", false);
                });
            }
        }
    });
}

function sectionPositionInput(sectionId) {
    var url = $url + 'setting/structure/branch-position';
    var branchId = $("#branchInput" + sectionId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId },
        success: function(data) {
            if (data.status || data.countSelect > 0) {
                //alert(data.select);
                $.each(data.select, function(key, val) {
                    $("#positionEdit" + sectionId + 'l' + val).css("display", "");
                    $("#position-input" + sectionId + 'l' + val).prop("checked", false);
                });
                $.each(data.unSelect, function(key, val) {
                    $("#positionEdit" + sectionId + 'l' + val).css("display", "none");
                    $("#position-input" + sectionId + 'l' + val).prop("checked", false);
                });
            } else {
                $.each(data.allPosition, function(key, val) {
                    $("#positionEdit" + sectionId + 'l' + val).removeAttr("checked");
                    $("#positionEdit" + val).css("display", "none");
                    $("#position-input" + sectionId + 'l' + val).prop("checked", false);
                });

            }
        }
    });
}

function checkedPosition(positionId) {
    if ($("#position" + positionId).is(':checked')) {
        $("#position" + positionId).attr('checked', 'checked');
        $("#position" + positionId).val(positionId);
    } else {
        $("#position" + positionId).removeAttr('checked');
    }
}