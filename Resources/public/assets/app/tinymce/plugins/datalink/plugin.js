tinymce.PluginManager.add('datalink', function (editor) {
    var utilities = {
        isDatalink: function (node) {
            return node.tagName == 'A' && editor.dom.getAttrib(node, 'data-link-id');
        },
        createLink: function (dataId, family) {
            var node = editor.selection.getNode();
            if (this.isDatalink(node)) {
                $(node).attr('data-link-id', dataId);
                $(node).attr('data-entity-name', family);
            } else {
                editor.editorCommands.execCommand('unlink');
                editor.insertContent('<a href="#data_' + dataId + '" data-link-id="' + dataId + '" data-entity-name="' + family + '">' +
                    editor.selection.getContent() +
                    '</a>');
            }
        }
    };

    function showDialog() {
        var selectedNode = editor.selection.getNode(), dataId = null, family = '';

        if (utilities.isDatalink(selectedNode)) {
            dataId = editor.dom.getAttrib(selectedNode, 'data-link-id');
        }

        editor.windowManager.open({
            title: 'Sélection d\'une donnée',
            url: Routing.generate('sunshine_tinymce_data_selector', {
                configName: 'datalink',
                dataId: dataId,
                family: family
            }),
            width: 500,
            height: 300
        });
    }

    editor.addCommand('mceDatalink', showDialog);

    editor.addButton('datalink', {
        icon: 'anchor',
        text: 'Datalink',
        tooltip: 'Datalink',
        onclick: showDialog,
        stateSelector: 'a[data-link-id]'
    });

    editor.addMenuItem('datalink', {
        icon: 'anchor',
        text: 'Datalink',
        context: 'insert',
        onclick: showDialog
    });

    return utilities;
});
