import axios from 'axios';

export const FETCH_MENU = 'FETCH_MENU';

export function fetchMenu( menu = null ) {
    const url = baseApp + 'menu';
    const request = axios.get(url);

    return {
        type: FETCH_MENU,
        payload: request
    };
}