import axios from 'axios';

export const FETCH_PAGE = 'FETCH_PAGE';

export function fetchPage( pageId ) {
    const url = baseApp + 'page/'+pageId;
    const request = axios.get(url);

    return {
        type: FETCH_PAGE,
        payload: request
    };
}