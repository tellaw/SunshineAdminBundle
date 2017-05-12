import axios from 'axios';

export const FETCH_CRUD_LIST = 'FETCH_CRUD_LIST';

export function fetchList( entityName, pageStart, length, searchKey, filters, orderBy, orderWay ) {
    const url = baseApp + 'crud/list/'+entityName;
    const request = axios.get(url);

    return {
        type: FETCH_CRUD_LIST,
        payload: request
    };
}