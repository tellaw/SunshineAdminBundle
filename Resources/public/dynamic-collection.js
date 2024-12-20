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
function addEmbeddedForm(collectionHolder, addButtonObj, collectionId) {

    // Vérification des données nécessaires
    if (!collectionHolder.data('prototype')) {
        return;
    }

    var prototype = collectionHolder.data('prototype');
    var index = collectionHolder.data('index') || 0;

    collectionHolder.data('index', index + 1);
    var divId = `${collectionId}_${index}`;
    prototype = prototype.replace(/__name__/g, index);

    if (addButtonObj.data('new-item-position') === 'before') {
        collectionHolder.prepend(`<div>${prototype}</div>`);
    } else {
        collectionHolder.append(`<div>${prototype}</div>`);
    }

    // Activer TinyMCE sur le nouvel élément
    var newTextarea = document.querySelector(`#${divId} .tinymce`);
    if (newTextarea) {
        activateTinyMCEIfNeeded(newTextarea);
    } 
    initializeCollections();
}


/**
 * Active TinyMCE sur un élément si ce n'est pas déjà activé.
 * @param {HTMLElement} element - L'élément cible (textarea).
 */
function activateTinyMCEIfNeeded(element) {
    if (!tinymce.get(element.id)) {
        tinymce.init({
            selector: `#${element.id}`,
            plugins: 'lists link code preview',
            toolbar: 'bold italic underline strikethrough | bullist numlist | outdent indent | link | removeformat preview code', // Boutons dans l'ordre demandé
            menubar: false,
            statusbar: true,
            skin: element.dataset.theme || 'default',
            height: 100, 
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }', 
        });
       
    } 
}



