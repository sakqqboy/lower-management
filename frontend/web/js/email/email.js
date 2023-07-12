var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/wikiinvestment/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;
$("#countryMail").change(function() {
    var countryId = $("#countryMail").val();
    var planId = $("#planMail").val();
    var url = $url + 'mail/default/search-member';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { countryId: countryId, planId: planId },
        success: function(data) {
            if (data.status) {
                $(".my-select").html();
                $(".my-select").html(data.options);
            }

        }
    });
});
$("#planMail").change(function() {
    var planId = $("#planMail").val();
    var countryId = $("#countryMail").val();
    var url = $url + 'mail/default/search-member';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { countryId: countryId, planId: planId },
        success: function(data) {
            $(".my-select").html();
            $(".my-select").html(data.options);

        }
    });
});