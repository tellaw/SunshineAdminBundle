$( document ).ready(function() {

    // Iterate to find widgets
    $("div").find("[data-type='widget.ajax']").each( function ( item ) {

        var route = $(this).data("route");
        $(this).load( route );

    });

    // DatePicker Init
    $('.date-picker').datepicker({format: 'dd/mm/yyyy'});

    // Alert auto hide
    $(".flashBagAlert").fadeTo(5000, 500).slideUp(500, function(){
        $(this).slideUp(500);
    });

});

function openWidgetEdit ( pageName, row, widgetName, itemId  ) {

    var route = $("#widget-"+widgetName).data("editroute")+"/"+itemId;
    $("#widget-"+widgetName).load ( route );

}
