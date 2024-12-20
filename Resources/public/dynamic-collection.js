jQuery(document).ready(function() {

    window.initializeCollections = function() {
        var collections = $(".dynamic-collection");
        collections.each(function () {

            var form = $(this).parents('form');
            var collectionId =  form.attr('name') +'_'+ $(this).data('for');
            var tabPane = $(this).parents().eq(3).closest('.tab-pane').attr('id');
            if (tabPane !== undefined) {
                collectionId =  form.attr('name') +'_'+ tabPane +'_'+ $(this).data('for');
            }

            var collectionHolder = $('#' + collectionId);
            if (collectionHolder.data('index') === undefined) {
                collectionHolder.data('index', collectionHolder.children().length);
            }

        });
    };

    $('body').on('click', '.btn.btn-info.pull-left', function(e) {
        e.preventDefault();
        var collectionId = $(this).closest('.dynamic-collection').attr('id');
        if (!collectionId) {
            return;
        }

        var collectionHolder = $('#' + collectionId);

        if (collectionHolder.length) {
            addEmbeddedForm(collectionHolder, $(this), collectionId);
        }
    });

    initializeCollections();

    // DELETE ONE FROM COLLECTION
    $('body').on('click', '.collection-delete', function(e) {
        e.preventDefault();
        if (confirm("Êtes-vous sûr?")) {
            console.log("[Delete] Suppression d'un élément");
            $.ajax({
                type: 'delete',
                url: Routing.generate('collection-delete', {entityName: $(this).data('entityname'), id: $(this).data('id')}),
                data: {},
                success: function () {
                    console.log("[Delete] Suppression réussie, rechargement de la page");
                    location.reload();
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

    // Vérifications des données nécessaires
    if (!collectionHolder.data('prototype')) {
        return;
    }
    if (collectionHolder.data('index') === undefined) {
        return;
    }

    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

    var divId = collectionId + '_' + (index + 1);

    // Remove the first label
    prototype = prototype.replace(/<label(.*)__name__label__<\/label>/i, '');

    // Replace __name__ by the correct index
    prototype = prototype.replace(/__name__/g, index + 1);

    prototype = prototype.replace('<div id="' + divId + '">', '<div id="' + divId + '" class="collectionForm col-lg-11">');

    if (addButtonObj.data('new-item-position') === 'before') {
        $("#" + collectionId).prepend('<div class="prototype list-group list-group-item row">' + prototype + '</div>');
    } else {
        $("#" + collectionId).append('<div class="prototype list-group list-group-item row">' + prototype + '</div>');
    }
}
