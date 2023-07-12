var $baseUrl = window.location.protocol + "/ / " + window.location.host;
if (window.location.host == 'localhost') {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/lower-management/frontend/web/';
} else {
    $baseUrl = window.location.protocol + "//" + window.location.host + '/';
}
$("#chart-fee-type").on('click', function(e) {
    $("#chart-fee-type-box").toggle();
    $("#angle-fee-down").toggle();
    $("#angle-fee-up").toggle();

});
$("#chart-type").on('click', function(e) {
    $("#chart-type-box").toggle();
    $("#angle-type-down").toggle();
    $("#angle-type-up").toggle();

});
$("#chart-vactor").on('click', function(e) {
    $("#chart-vactor-box").toggle();
    $("#angle-vactor-down").toggle();
    $("#angle-vactor-up").toggle();

});
$("#termType").on('change', function(e) {
    var type = $("#termType").val();
    var url = $url + 'mms/default/formular';
    $("#input-formular").html('');
    if (type != "") {
        if (type == 4) {
            $("#first-year").show();
        } else {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: { type: type },
                success: function(data) {
                    if (data) {
                        $("#input-formular").html(data.text);
                        $("html, body").animate({
                            scrollTop: $(
                                'html, body').get(0).scrollHeight
                        }, 500);
                        $("#save-cal").css("display", "none");
                    }

                }
            });
        }
    } else {
        $("#input-formular").html('');
    }
});
$("#first-year").on('keyup', function(e) {
    var url = $url + 'mms/default/formular';
    var type = 4;
    var startYear = $("#first-year").val();
    if (startYear != "") {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { type: type, startYear: startYear },
            success: function(data) {
                if (data) {
                    $("#input-formular").html(data.text);
                    $("html, body").animate({
                        scrollTop: $(
                            'html, body').get(0).scrollHeight
                    }, 500);
                    $("#save-cal").css("display", "none");
                }
            }
        });
    } else {
        $("#input-formular").html('');
    }
});

function caculateGraph(save) {
    var row = 5;
    var totalColumn = $("#totalColumn").val();
    var formula = $("#formula-text").val();
    var totalRow = $('#last-row').val();
    var row = 1;
    var col = 0;
    dataInput = [];
    while (row <= totalRow) {
        col = 0;
        while (col < totalColumn) {
            text = "row" + row + "-" + col;
            dataInput.push($("#" + text).val());
            col++;
        }
        row++;
    }
    //alert(dataInput);
    var url = $url + 'mms/default/calculate-result';
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { dataInput: dataInput, totalColumn: totalColumn, formula: formula, totalRow: totalRow },
        success: function(data) {
            if (data.status) {
                $.each(data.result, function(key, val) {
                    $("#column-" + key).html(val);
                    $("#result-" + key).val(val);
                });
                $("#save-cal").show();
            }
        }
    });


}

function addRow(total) {
    var lastRow = $("#last-row").val();
    var currentRow = parseInt(lastRow) + 1;
    $('#more-row').append("<tr>");
    $('#more-row').append("<td><input type='text' class='formular-input td-width-120'  placeholder='r" + currentRow + "' name='row" + currentRow + "'></td>");
    var i = 0;

    while (i < total) {
        $('#more-row').append("<td><input type='text' class='text-right formular-input' name='dataRow[" + currentRow + "][" + i + "]' id='row" + currentRow + "-" + i + "'></td>");
        i++;
    }
    $('#last-row').val(currentRow);
    $("html, body").animate({
        scrollTop: $(
            'html, body').get(0).scrollHeight
    }, 500);

}
/*$("#term-type").on('click', function(e) {
    var term = $("#term-type").val();
    alert(term)
         var url = $url + 'job/detail/search-job';
         $.ajax({
             type: "POST",
             dataType: 'json',
             url: url,
             data: { term: term },
             success: function(data) {
                 if (data) {
                     //$("#job-result").html(data.text);
                 }

             }
         });
});*/
$("#type-pie").on('change', function(e) {
    if ($("#type-pie").prop('checked') == true) {
        //alert(1);
        $("#other-chart").css("display", "none");
        $("#fillOut").css("display", "none");
        $("#pie-chart").show();
    } else {
        $("#other-chart").show();
        $("#fillOut").show();
        $("#pie-chart").css("display", "none");
    }
});
$("#type-line").on('change', function(e) {
    if ($("#type-line").prop('checked') == true) {
        $("#other-chart").show();
        $("#pie-chart").css("display", "none");
        $("#fillOut").show();
    }
});
$("#type-bar").on('change', function(e) {
    if ($("#type-bar").prop('checked') == true) {
        $("#other-chart").show();
        $("#pie-chart").css("display", "none");
        $("#fillOut").show();
    }
});
$("#pie-piece").on('keyup', function(e) {
    var number = $("#pie-piece").val();
    var dataTypeInput = $("#dataType").val();
    var url = $url + 'mms/default/render-pie';
    var chartName = $("#chart-name").val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url,
        data: { number: number, chartName: chartName, dataTypeInput: dataTypeInput },
        success: function(data) {
            if (data.status) {
                $("#show-pie").html(data.text);
            } else {
                $("#show-pie").html('');
            }
        }
    });
});

function setDataType(type) {
    $("#dataType").val(type);
    generatePieGraph();
}

function generatePieGraph() {
    var totalPiece = $("#total-piece").val();
    var dataTypeInput = $("#dataType").val();
    var title = [];
    var pieceValue = [];
    var chartName = $("#chart-name").val();
    var i = 0;
    if (totalPiece != '' && totalPiece != null) {
        while (i < totalPiece) {
            title.push($("#piece" + i).val());
            pieceValue.push($("#piece-value-" + i).val());
            i++;
        }
        var url = $url + 'mms/default/generate-pie-chart';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: { title: title, pieceValue: pieceValue, totalPiece: totalPiece, chartName: chartName, dataTypeInput: dataTypeInput },
            success: function(data) {
                if (data.status) {
                    $("#show-pie-chart").html(data.text);
                } else {
                    $("#show-pie-chart").html('');
                }
            }
        });
    }

}