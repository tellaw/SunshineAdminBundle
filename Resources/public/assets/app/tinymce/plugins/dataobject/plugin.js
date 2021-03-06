tinymce.PluginManager.add('dataobject', function (editor) {
    var utilities = {
        isDataobject: function (node) {
            return node.tagName == 'IMG' && editor.dom.getAttrib(node, 'data-object-id');
        },
        createObject: function (dataId, family, dataName) {
            var node = editor.selection.getNode();
            if (this.isDataobject(node)) {
                $(node).attr('data-object-id', dataId);
                $(node).attr('data-family', family);
            } else {
                editor.insertContent('<div><img class="dataobject" title="' + dataName + '" data-family="' + family + '" data-object-id="' + dataId + '" src="/build/img/dataobject.png" /></div>');
            }
        }
    };

    function showDialog() {
        var selectedNode = editor.selection.getNode(), dataId = null, family = '';

        if (utilities.isDataobject(selectedNode)) {
            dataId = editor.dom.getAttrib(selectedNode, 'data-object-id');
            family = editor.dom.getAttrib(selectedNode, 'data-family');
        }

        editor.windowManager.open({
            title: 'Sélection d\'un objet',
            url: Routing.generate('sunshine_tinymce_data_selector', {
                configName: 'dataobject',
                dataId: dataId,
                family: family
            }),
            width: 500,
            height: 300
        });
    }

    editor.addCommand('mceDataobject', showDialog);

    editor.addButton('dataobject', {
        icon: 'anchor',
        text: 'Dataobject',
        tooltip: 'Insérer un objet',
        onclick: showDialog,
        stateSelector: 'img[data-object-id]'
    });

    editor.addMenuItem('dataobject', {
        icon: 'anchor',
        text: 'Dataobject',
        context: 'insert',
        onclick: showDialog
    });

    return utilities;
});
