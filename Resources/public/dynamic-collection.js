jQuery(document).ready(function() {

    var collections = $(".dynamic-collection");
    collections.each(function () {

        var form = $(this).parents('form');
        var collectionId =  form.attr('name') +'_'+ $(this).data('for');
        var tabPane = $(this).parents().eq(3).closest('.tab-pane').attr('id');
        if (tabPane !== undefined) {
          collectionId =  form.attr('name') +'_'+ tabPane +'_'+ $(this).data('for');
        }
        var collectionHolder = $('#'+collectionId);

        var index = collectionHolder.children().length;
        collectionHolder.data('index', index);

        if (collectionHolder.data("prototype") != undefined) {

            // Adding ADD Button
            var buttonId = '#button_add_'+ collectionId;
            var addButton = $(buttonId);

            addButton.on('click', function(e) {
                e.preventDefault();
                addEmbeddedForm(collectionHolder, addButton, collectionId);
            });

        }
    });

    // DELETE ONE FROM COLLECTION
    $('body').on('click', '.collection-delete', function(e) {
        e.preventDefault();
        if (confirm("Êtes-vous sûr?")) {
            $.ajax({
                type: 'delete',
                url: Routing.generate('collection-delete', {entityName: $(this).data('entityname'), id: $(this).data('id')}),
                data: {},
                success: function () {
                    location.reload(true);
                }
            });
        }
    });

});

/**
 * Display subform
 *
 * @param collectionHolder
 * @param addButtonObj
 * @param collectionId
 */
function addEmbeddedForm(collectionHolder, addButtonObj, collectionId)
{
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    var $label = collectionHolder.data('label');

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);
    var divId = collectionId + '_' + ( index + 1);

    // Remove the first label
    var prototype = prototype.replace(/<label(.*)__name__label__<\/label>/i, '');

    // Replace __name__ by the correct index
    prototype = prototype.replace(/__name__/g, index + 1);

    prototype = prototype.replace ('<div id="'+divId+'">','<div id="'+divId+'" class="collectionForm col-lg-11">');

    $("#"+collectionId).append( '<div class="prototype list-group list-group-item row">'+prototype+'</div>' );
    $('<div class="collectionDeleteButton col-lg-1"><a href="#" class="remove-tag btn btn-danger">x</a></div>').insertBefore ('#'+divId);

    $('#'+divId).parent().wrapInner("<div class='row'></div>");

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {
        collectionHolder.data('index', index);
        e.preventDefault();
        $(this).parent().parent().parent().parent().remove();

        return false;
    });

}
