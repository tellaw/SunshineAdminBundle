jQuery(document).ready(function() {
    var $form = $("form");
    var $collections = $(".dynamic-collection");
    $collections.each(function () {
        var $collectionId =  $form.attr('name') +'_'+ $(this).data('for');

        var $buttonId = '#add_'+ $collectionId;
        var $addTagLink = $($buttonId);
        var $newLinkDiv = $('<div></div>').append($addTagLink);

        var $collectionHolder = $('div#'+$collectionId);
        var index = 0;
        console.log($newLinkDiv);

        $collectionHolder.append($newLinkDiv);

        var $prototype = $("<div/>").append($collectionHolder.data("prototype"));
        if ($prototype.find(':input').length > 1)
        {
            index = $collectionHolder.find('*[data-unique="data-unique"]').length;
        } else
        {
            index = $collectionHolder.find(':input').length;
        }
        $collectionHolder.data('index', index);

        $addTagLink.on('click', function(e) {
            e.preventDefault();
            addAttachmentForm($collectionHolder, $newLinkDiv);
            $.getScript("/bundles/tellawsunshineadmin/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js");
            $.getScript("/bundles/tellawsunshineadmin/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js");
        });
    });


    // DELETE ONE FROM COLLECTION
    $('body').on('click', '.collection-delete', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'delete',
            url: Routing.generate('collection-delete', {entityName: $(this).data('entityname'), id: $(this).data('id')}),
            data: {},
            success: function() {
                location.reload();
            }
        });
    });

});

function addAttachmentForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    // get the new index
    var index = $collectionHolder.data('index');
    var $label = $collectionHolder.data('label');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have

    var newForm = prototype.replace(/__name__label__/, (index));
    newForm = newForm.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    var $newFormDiv = $('<div></div>').append($(newForm));

    // also add a remove button, just for this example
    $newFormDiv.append('<a href="#" class="remove-tag btn btn-danger">x</a>');

    $newLinkLi.before($newFormDiv);

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {
        $collectionHolder.data('index', index);
        e.preventDefault();
        $(this).parent().remove();

        return false;
    });
}