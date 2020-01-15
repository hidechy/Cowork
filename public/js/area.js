
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$("#select_area").change(function (){

    $("#select_pref").html("");
    $("#select_line").html("");
    $("#select_station").html("");

    $("#error_text").html("");
    $("#station_code").val("");

    $.ajax({
    url: '{{ $appUrl }}/callpref',
    type: 'POST',
    data: {
        _token: CSRF_TOKEN ,
        'area' : $("#select_area").val()
    },
    success: function (data) {
        if (data != ""){
        let ex_data = (data).split("/");
        let str = "<option></option>";
        for (let i=0 ; i<ex_data.length ; i++){
            let ex_val = (ex_data[i]).split("|");
            str += "<option value='" + ex_val[0] + "'>" + ex_val[1] + "</option>";
        }
        $("#select_pref").html(str);
        }
    }
    });
});



$("#select_pref").change(function (){

    $("#select_line").html("");
    $("#select_station").html("");

    $("#error_text").html("");
    $("#station_code").val("");

    $.ajax({
    url: '{{ $appUrl }}/callline',
    type: 'POST',
    data: {
        _token: CSRF_TOKEN ,
        'pref' : $("#select_pref").val()
    },
    success: function (data) {
        if (data != ""){
        let ex_data = (data).split(";");
        let ex_data0 = (ex_data[0]).split("/");
        let str = "<option></option>";
        for (let i=0 ; i<ex_data0.length ; i++){
            let ex_val = (ex_data0[i]).split("|");
            str += "<option value='" + ex_val[0] + "'>" + ex_val[1] + "</option>";
        }
        $("#select_line").html(str);

        $("#station_code").val(ex_data[1]);
        }else{
        $("#error_text").html("<div style='padding: 5px;'>検索結果がありません。</div>");
        }
    }
    });
});



$("#select_line").change(function (){

    $("#select_station").html("");

    $("#error_text").html("");

    $.ajax({
    url: '{{ $appUrl }}/callstation',
    type: 'POST',
    data: {
        _token: CSRF_TOKEN ,
        'line' : $("#select_line").val() , 
        'station_code' : $("#station_code").val() , 
    },
    success: function (data) {
        if (data != ""){
        let ex_data = (data).split("/");
        let str = "<option></option>";
        for (let i=0 ; i<ex_data.length ; i++){
            let ex_val = (ex_data[i]).split("|");
            str += "<option value='" + ex_val[0] + "'>" + ex_val[1] + "</option>";
        }
        $("#select_station").html(str);
        }
    }
    });
});
