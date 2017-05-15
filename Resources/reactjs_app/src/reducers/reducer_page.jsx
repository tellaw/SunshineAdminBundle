import { FETCH_PAGE } from '../actions/action_page.jsx';

export default function(state = null, action) {

    switch (action.type) {
        case FETCH_PAGE:
            return action.payload.data;
    }
    return state;
}