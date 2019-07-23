tinymce.PluginManager.add('leadsfactory', function (editor) {
    var utilities = {
        isLeadsFactory: function (node) {
            return node.tagName == 'IMG' && editor.dom.getAttrib(node, 'data-leadsfactory-id');
        },
        createObject: function (dataId) {
            var node = editor.selection.getNode();
            if (this.isLeadsFactory(node)) {
                $(node)
                    .attr('data-leadsfactory-id', dataId);
            } else {
                editor.insertContent('<div><img class="leadsfactory" data-leadsfactory-id="' + dataId + '" src="/build/img/leadsfactory.png" /></div>');
            }
        }
    };

    function showDialog() {
        var selectedNode = editor.selection.getNode(), dataId = null;

        if (utilities.isLeadsFactory(selectedNode)) {
            dataId = editor.dom.getAttrib(selectedNode, 'data-leadsfactory-id');
        }

        editor.windowManager.open({
            title: 'Formulaire LeadsFactory',
            url: Routing.generate('sunshine_tinymce_leadsfactory_form_selector', {
                dataId: dataId
            }),
            width: 500,
            height: 300
        });
    }

    editor.addCommand('mceLeadsFactory', showDialog);

    editor.addButton('leadsfactory', {
        icon: 'anchor',
        text: 'LeadsFactory',
        tooltip: 'Insérer un formulaire Leads Factory',
        onclick: showDialog,
        stateSelector: 'img[data-leadsfactory-id]'
    });

    editor.addMenuItem('leadsfactory', {
        icon: 'anchor',
        text: 'LeadsFactory',
        context: 'insert',
        onclick: showDialog
    });

    return utilities;
});
