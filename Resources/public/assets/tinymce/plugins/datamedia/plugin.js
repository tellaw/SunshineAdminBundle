tinymce.PluginManager.add('datamedia', function (editor) {
    var utilities = {
        isDatamedia: function (node) {
            return node.tagName == 'IMG' && editor.dom.getAttrib(node, 'data-media-id');
        },
        createMedia: function (dataId, filter, isResponsive) {
            var node = editor.selection.getNode();
            var media = null;
            if (this.isDatamedia(node)) {
                media = $(node);
            } else {
                media = $('<img>');
            }
            let url = Routing.generate('sunshine_attachment_url', {
                id: dataId,
                filter: filter
            });
            media.attr('src', url)
                .attr('data-mce-src', url)
                .attr('data-media-id', dataId)
                .attr('data-media-filter', filter);

            if (isResponsive) {
                media.attr('class', 'img-responsive');
            } else {
                media.attr('class', '');
            }

            if (!this.isDatamedia(node)) {
                editor.selection.collapse();
                editor.insertContent($('<div>').append(media).html());
            }
        }
    };

    function showDialog() {
        var selectedNode = editor.selection.getNode(), attachmentId = null, dataFilter = null, dataResponsive = null;

        if (utilities.isDatamedia(selectedNode)) {
            attachmentId = editor.dom.getAttrib(selectedNode, 'data-media-id');
            dataFilter = editor.dom.getAttrib(selectedNode, 'data-media-filter');
            if (editor.dom.getAttrib(selectedNode, 'class').indexOf("img-responsive") >= 0) {
                dataResponsive = 1;
            } else {
                dataResponsive = 0;
            }
        }

        editor.windowManager.open({
            title: 'Sélection d\'un média',
            url: Routing.generate('sunshine_tinymce_media_form_selector', {
                dataId: attachmentId,
                dataFilter: dataFilter,
                dataResponsive: dataResponsive
            }),
            width: 995,
            height: 600
        });
    }

    editor.addCommand('mceDatamedia', showDialog);

    editor.addButton('datamedia', {
        icon: 'image',
        text: 'Datamedia',
        tooltip: 'Insérer une image',
        onclick: showDialog,
        stateSelector: 'img[data-media-id]'
    });

    editor.addMenuItem('datamedia', {
        icon: 'image',
        text: 'Datamedia',
        context: 'insert',
        onclick: showDialog
    });

    return utilities;
});
