var Fetch = require ('whatwg-fetch');

module.export = window.api = {

    get: function ( pageId ) {
        return fetch (baseApp + 'page/'+pageId)
        .then (function(response) {
            return response.json();
        })
    }
}
