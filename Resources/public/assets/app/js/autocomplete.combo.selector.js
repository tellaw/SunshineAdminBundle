/**
 *
 * @param target
 */
function updateComboSelector(target)
{
    var form = target.closest('form');
    var familySelect = form.find('#form_comboEntitySelector_family');
    $.post({
        url : form.attr('action'),
        data : {'form[comboEntitySelector][family]': familySelect.val()},
        success: function(html) {
            $('#form_comboEntitySelector_entity').select2('destroy');
            $('#form_comboEntitySelector_entity').replaceWith(
                $(html).find('#form_comboEntitySelector_entity')
            );

            $('#form_comboEntitySelector_entity').select2();
        }
    });
}

function initComboSelector(target)
{
    $(target).find('select.family-selector').each(function(){
        var t = $(this);
        if (t.val()) {
            // updateComboSelector(t);
        }
        t.on('change', function(){
            updateComboSelector(t);
        });
    });
}

!function ($) {
    $(document).ready(function() {
        initComboSelector(document);
    });
}(window.jQuery);
