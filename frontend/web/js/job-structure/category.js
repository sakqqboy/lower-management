var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;

function updateCategory(categoryId) {
    var url = $url + 'setting/job-structure/update-category';
    var categoryName = $("#categoryNameInput" + categoryId).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { categoryId: categoryId, categoryName: categoryName },
        success: function(data) {
            if (data.status) {
                $("#tr-edit" + categoryId).hide();
                $("#categoryName" + categoryId).html(data.categoryName);
            }
        }
    });
}

function disableCategory(categoryId) {
    var url = $url + 'setting/job-structure/disable-category';
    if (confirm('Becareful, if category is deleted, job will be deleted!!! Are you sure to detete ?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { categoryId: categoryId },
            success: function(data) {
                if (data.status) {
                    $("#category" + categoryId).hide();
                }
            }
        });
    }
}