var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateField(fieldId) {
    var url = $url + 'setting/job-structure/update-field';
    var fieldName = $("#fieldNameInput" + fieldId).val();
    var branchId = $("#branch-" + fieldId).val();
    var subFieldGroupId = $("#subFieldGroup-" + fieldId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { fieldId: fieldId, fieldName: fieldName, subFieldGroupId: subFieldGroupId, branchId: branchId },
        success: function(data) {
            if (data.status) {
                window.location.reload();
            }
        }
    });
}

function disableField(fieldId) {
    var url = $url + 'setting/job-structure/disable-field';
    if (confirm('Becareful, if field is deleted, job will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { fieldId: fieldId },
            success: function(data) {
                if (data.status) {
                    $("#field" + fieldId).hide();
                }
            }
        });
    }
}

function filterField() {
    var url = $url + 'setting/job-structure/search-field';
    var branchId = $("#branchSearchField").val();
    var subFieldGroupId = $("#groupSearchField").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { branchId: branchId, subFieldGroupId: subFieldGroupId },
        success: function(data) {
            if (data.status) {
                $("#field-search").html(data.text);
            }
        }
    });
}