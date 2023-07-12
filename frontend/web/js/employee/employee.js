var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$url = $baseUrl;
$("#branch").change(function() {
    var id = $("#branch").val();
    $("#position").prop("disabled", true);
    var url = $url + 'setting/employee/section';
    var text = "Section";

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { id: id, text: text },
        success: function(data) {
            if (data.status) {
                if (data.text != '') {
                    $("#section").removeAttr("disabled");
                    $("#section").html(data.text);
                } else {
                    $("#section").attr("disabled", "disabled");
                    $("#section").html('<option>' + text + '</option>');
                }
                if (data.textTeam != '') {
                    $("#team-create").html(data.textTeam);
                } else {
                    $("team-create").html('<option value="">Dream Team</option>');
                }

            }

        }
    });
});
$("#section").change(function() {
    var id = $("#section").val();
    var url = $url + 'setting/employee/position';
    var text = "Position";
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { id: id, text: text },
        success: function(data) {
            if (data.status) {
                if (data.text != '') {
                    $("#position").removeAttr("disabled");
                    $("#position").html(data.text);
                } else {
                    $("#position").attr("disabled", "disabled");
                    $("#position").html('<option value="">' + text + '</option>');
                }

            }

        }
    })
});


function checkEmail() {
    var url = $url + 'setting/employee/duplicate-email';
    var email = $("#employeeEmail").val();
    var employeeId = $("#em").val();
    if (email)
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { email: email, employeeId: employeeId },
            success: function(data) {
                if (data.status) {
                    $("#invalid-input").css("display", "none");
                } else {
                    $("#invalid-input").css("display", "inline-block");
                    $("#employeeEmail").val('');

                }
            }
        });

}

function disableEmployee(id) {
    var url = $url + 'setting/employee/disable-employee';
    if (confirm('Are you sure to delete this employee?')) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { id: id },
            success: function(data) {
                if (data.status) {
                    location.reload();
                }
            }
        });
    }
}
$("#branch-search-employee").change(function() {
    var id = $("#branch-search-employee").val();
    var url = $url + 'setting/employee/section';
    var text = "Section";
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { id: id, text: text },
        success: function(data) {
            if (data.status) {
                if (data.text != '') {
                    $("#section-search-employee").html(data.text);
                } else {
                    $("#section-search-employee").html('<option value="">' + text + '</option>');
                }
                if (data.textTeam != '') {
                    $("#team-employee").html(data.textTeam);
                } else {
                    $("team-employee").html('<option value="">Team</option>');
                }
                if (data.textPosition != '') {
                    $("#position-search-employee").html(data.textPosition);
                } else {
                    $("position-search-employee").html('<option value="">Team</option>');
                }
                filterEmployee();
            }

        }
    })
});
$("#section-search-employee").change(function() {
    filterEmployee();
    /* var id = $("#section-search-employee").val();
     
     var url = $url + 'setting/employee/position';
     var text = "Position";
     $.ajax({
         type: "POST",
         dataType: 'json',
         url: url,
         data: { id: id, text: text },
         success: function(data) {
             if (data.status) {
                 if (data.text != '') {
                     $("#position-search-employee").html(data.text);
                 } else {
                     $("#position-search-employee").html('<option value="">' + text + '</option>');
                 }

             }
             
         }
     });*/
});

function filterEmployee() {
    var searchName = $("#searchName").val();
    var branchId = $("#branch-search-employee").val();
    var sectionId = $("#section-search-employee").val();
    var positionId = $("#position-search-employee").val();
    var teamId = $("#team-employee").val();
    var typeId = $("#user-type-search-employee").val();
    var url = $url + 'setting/employee/search-employee';
    //alert(branchId + '=>' + sectionId + '=>' + positionId + '=>' + teamId + '=>' + typeId)
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { searchName: searchName, branchId: branchId, sectionId: sectionId, positionId: positionId, teamId: teamId, typeId: typeId },
        success: function(data) {
            if (data) {
                $("#employee-result").html(data.text);
            }

        }
    });
}

function checkRight(typeId, employeeId) {
    var url = $url + 'setting/employee/check-right';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { employeeId: employeeId, typeId: typeId },
        success: function(data) {

        }
    });
}

function filterEmployeeRight() {
    var searchText = $("#search-name-right").val();
    var branchId = $("#branch-search-right-employee").val();
    var url = $url + 'setting/employee/search-employee-right';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { searchText: searchText, branchId: branchId },
        success: function(data) {
            if (data.status) {
                $("#employee-right-result").html(data.text);
            }
        }
    });
}

function disableSomeEmployee() {
    var total = $("#totalEmployee").val();
    var i = 1;
    var a = 0;
    var allId = [];
    while (i <= total) {
        if ($("#deleteEmployee" + i).prop("checked") == true) {
            allId[i] = $("#deleteEmployee" + i).val();
            a++;
        }

        i++;
    }
    if (parseInt(allId.length) > 1) {
        var total = allId.length - 1;
        if (confirm('Are you sure to delete ' + a + ' employee(s)')) {
            var url = $url + 'setting/employee/disable-some-employee';
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { employeeId: allId },
                success: function(data) {
                    if (data.status) {
                        location.reload();
                    }
                }
            });
        }
    }
}

function changePassword() {
    var oldPassword = $("#old-password").val();
    var newPassword = $("#new-password").val();
    var conFirmPassword = $("#confirm-password").val();
    if (oldPassword != '' && newPassword != '' && conFirmPassword != '') {
        var url = $url + 'profile/default/change-password';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { oldPassword: oldPassword, newPassword: newPassword, conFirmPassword: conFirmPassword },
            success: function(data) {
                if (data.status) {
                    window.location.href = $url + 'profile/default/index';
                } else {
                    alert(data.text);
                }
            }
        });
    } else {
        alert('Please fill out all fields.');
    }
}