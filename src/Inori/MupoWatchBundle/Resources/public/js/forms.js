$('#report_type').live('change', function() {
    $.getJSON(ajax_transport, {
        type: $('#report_type').val()
    }, function(data) {
        var temp = {};
        temp[0] = 'Number';
        for (var i = 0; i < data.length; i++) {
            temp[data[i].id] = data[i].number;
        }
        $("#report_number").selectBox('options', temp);
    });
});  
            
$('#report_number').live('change', function() {
    $.getJSON(ajax_destination, {
        transport: $('#report_number').val()
    }, function(data) {
        var temp = {};
        temp[0] = 'Suund';
        for (var i = 0; i < data.length; i++) {
            temp[data[i].id] = data[i].name;
        }
        $("#report_destination").parent().find('a').css('width', '200px');
        $("#report_destination").selectBox('options', temp);
    });
}); 
            
$('#report_destination').live('change', function() {
    $.getJSON(ajax_station, {
        destination: $('#report_destination').val()
    }, function(data) {
        var temp = {};
        temp[0] = 'Peatus';
        for (var i = 0; i < data.length; i++) {
            temp[data[i].id] = data[i].name;
        }
        $("#report_stationBefore").parent().find('a').css('width', '140px');
        $("#report_stationBefore").selectBox('options', temp);
    });
}); 