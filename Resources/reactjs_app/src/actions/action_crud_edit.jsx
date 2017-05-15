import axios from 'axios';

export const FETCH_CRUD_EDIT = 'FETCH_CRUD_EDIT';

export function fetchId( entityName, targetId ) {
    const url = baseApp + 'crud/edit/'+entityName+'/'+targetId;
    const request = axios.get(url);

    return {
        type: FETCH_CRUD_EDIT,
        payload: request
    };
}