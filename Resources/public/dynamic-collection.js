jQuery(document).ready(function() {
    var $form = $("form");
    var $collections = $(".dynamic-collection");
    $collections.each(function () {
        var $collectionId =  $form.attr('name') +'_'+ $(this).data('for');

        var $buttonId = '#add_'+ $collectionId;
        var $addTagLink = $($buttonId);
        var $newLinkLi = $('<div></div>').append($addTagLink);

        var $collectionHolder = $('div#'+$collectionId);

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagLink.on('click', function(e) {
            e.preventDefault();
            addAttachmentForm($collectionHolder, $newLinkLi);
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

    var newForm = prototype.replace(/__name__label__/, $label +' ' +(index+1));
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // var parser = new DOMParser();
    // var doc = parser.parseFromString(newForm, "text/xml");
    // $(doc).find('label').remove();

    // Display the form in the page in an li, before the "Add a tag" link li
    // var $newFormDiv = $('<div></div>').append($(doc.childNodes).html());
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