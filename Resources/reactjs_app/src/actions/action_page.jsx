import axios from 'axios';

export const FETCH_PAGE = 'FETCH_PAGE';

export function resetPage(  ) {
    return {
        type: RESET_PAGE,
        payload: null
    };
}

export function fetchPage( pageId ) {
    const url = baseApp + 'page/'+pageId;
    const request = axios.get(url);

    return {
        type: FETCH_PAGE,
        payload: request
    };
}