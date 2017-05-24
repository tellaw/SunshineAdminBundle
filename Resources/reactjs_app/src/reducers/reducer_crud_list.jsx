import { FETCH_CRUD_LIST } from '../actions/action_crud_list.jsx';
import { RESET_CRUD_LIST } from '../actions/action_crud_list.jsx';

export default function(state = null, action) {

    switch (action.type) {
        case FETCH_CRUD_LIST:
            return action.payload.data;

        case RESET_CRUD_LIST:
            return action.payload;
    }
    return state;
}