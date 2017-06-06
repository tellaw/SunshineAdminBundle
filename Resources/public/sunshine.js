$( document ).ready(function() {

    // Iterate to find widgets
    $("div").find("[data-type='widget.ajax']").each( function ( item ) {

        var route = $(this).data("route");
        $(this).load( route );

    });

});