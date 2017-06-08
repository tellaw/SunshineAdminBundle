$( document ).ready(function() {

    // Iterate to find widgets
    $("div").find("[data-type='widget.ajax']").each( function ( item ) {

        var route = $(this).data("route");
        $(this).load( route );

    });

});

function openWidgetEdit ( pageName, row, widgetName, itemId  ) {

    var route = $("#widget-"+widgetName).data("editroute")+"/"+itemId;
    $("#widget-"+widgetName).load ( route );

}