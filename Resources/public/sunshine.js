$( document ).ready(function() {

    // Iterate to find widgets
    $("div").find("[data-type='widget.ajax']").each( function ( item ) {
       console.log ($(this).data("route"));

       var widgetId = $(this).id;
        var route = $(this).data("route");

        $(this).load( route );

    });

});