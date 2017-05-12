var Fetch = require ('whatwg-fetch');

export const CrudApiList = ( entity ) => {

    return fetch (baseApp + 'crud/list/'+entity+'/')
    .then (function(response) {
        return response.json();
    })

}
