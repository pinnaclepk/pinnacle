$(document).ready(function() {
    
    $('#to2').click(function() {
        console.log("test");
        var selectedValues = $("#AvailableGuard").find("option:selected")
        $(selectedValues).each(function(index) {
            $('#SelectedGuard').append($(this)[0].outerHTML);
        });
        $(selectedValues).remove();
    });

    $('#allTo2').click(function() {
        var selectedValues = $("#AvailableGuard").find("option:selected")
        $('select#AvailableGuard').find('option').each(function(index) {
            $('#SelectedGuard').append($(this)[0].outerHTML);
        });
        $('#AvailableGuard').find('option').remove();
    });

    $('#to1').click(function() {
        var selectedValues = $("#SelectedGuard").find("option:selected")
        $(selectedValues).each(function(index) {
            $('#AvailableGuard').append($(this)[0].outerHTML);
        });
        $(selectedValues).remove();
    });

    $('#allTo1').click(function() {
        var selectedValues = $("#SelectedGuard").find("option:selected")
        $('select#SelectedGuard').find('option').each(function(index) {
            $('#AvailableGuard').append($(this)[0].outerHTML);
        });
        $('#SelectedGuard').find('option').remove();
    });

});

function selectAllValSiteGuard() {
    $('#SelectedGuard option').prop('selected', 'selected');
    return true;
}