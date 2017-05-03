var Fetch = require ('whatwg-fetch');

module.export = window.api = {

    get: function () {
        return fetch ( "http://local.dev/teamtracking/web/app_dev.php/admin/menu"  )
        .then (function(response) {
            return response.json()
        })
        .then (function (data) {
            console.log (data);
        })
    }

}