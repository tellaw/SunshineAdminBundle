var Fetch = require ('whatwg-fetch');

module.export = window.api = {

    get: function () {
        return fetch ('http://dev.local/web/app_dev.php/admin/menu')
        .then (function(response) {
            return response.json();
        })
    }
}
