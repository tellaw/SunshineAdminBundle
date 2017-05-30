import axios from 'axios';

export const FETCH_CRUD_LIST = 'FETCH_CRUD_LIST';
export const RESET_CRUD_LIST = 'RESET_CRUD_LIST';

export function resetCrudList(  ) {
    return {
        type: RESET_CRUD_LIST,
        payload: null
    };
}

export function fetchList( entityName, pageStart=0, length=10, searchKey='', filters=null, orderBy, orderWay ) {
    var url = baseApp + 'crud/list/'+entityName+'/'+pageStart+'/'+length+'?searchKey='+searchKey;
    if (filters != null) {
        url += '&'+filters;
    }

    const request = axios.get(encodeURI(url));

    return {
        type: FETCH_CRUD_LIST,
        payload: request
    };
}
