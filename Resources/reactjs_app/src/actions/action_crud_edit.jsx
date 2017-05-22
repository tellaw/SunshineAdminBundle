import axios from 'axios';
import { contextUpdate } from './action_context.jsx';

export const FETCH_CRUD_EDIT = 'FETCH_CRUD_EDIT';
export const POST_CRUD_EDIT = 'POST_CRUD_EDIT';
export const RESET_CRUD_EDIT = 'RESET_CRUD_EDIT';

export function fetchCrudEdit( entityName, targetId ) {
    const url = baseApp + 'crud/edit/'+entityName+'/'+targetId;
    const request = axios.get(url);

    return {
        type: FETCH_CRUD_EDIT,
        payload: request
    };
}

export function resetCrudEdit(  ) {
    return {
        type: RESET_CRUD_EDIT,
        payload: null
    };
}

export function postForm ( entityName, targetId, data ) {

    const url = baseApp + 'crud/post/'+entityName+'/'+targetId;
    console.log (data);
    const request = axios.post(url, data, {headers:{'Content-Type':'application/json'}});

    return {
        type: POST_CRUD_EDIT,
        payload: request
    };

}