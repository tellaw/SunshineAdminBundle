import { FETCH_MENU } from '../actions/action_menu.jsx';

export default function(state = null, action) {

    switch (action.type) {
        case FETCH_MENU:
            return action.payload.data;
    }
    return state;
}