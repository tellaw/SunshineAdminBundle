$(document).ready(function () {
    // Iterate to find widgets
    $("div").find("[data-type='widget.ajax']").each(function (item) {

        var route = $(this).data("route");
        $(this).load(route);

    });
    // DatePicker Init
    $('.date-picker').datepicker({
        format: 'dd/mm/yyyy',
        startDate: "now",
        language: 'fr'
    });

    $('.datetime-picker').datetimepicker({format: 'dd/mm/yyyy hh:ii'});

    $('.select-picker').selectpicker();

    // Alert auto hide
    $(".flashBagAlert").fadeTo(5000, 500).slideUp(500, function () {
        $(this).slideUp(500);
    });

    // remove tags

    $(".bootstrap-tagsinput").on("click", '[data-role=remove]', function (e) {
        var $value = $(this).closest('.tag').text();
        var $input = $(".bootstrap-tagsinput").parent('div').find('*[data-role="tagsinput"]');
        $inputValue = $input.attr('value').replace($value, '');
        $($input).attr('value', $inputValue);
    });

});

function openWidgetEdit(pageName, row, widgetName, itemId) {

    var route = $("#widget-" + widgetName).data("editroute") + "/" + itemId;
    $("#widget-" + widgetName).load(route);

}
