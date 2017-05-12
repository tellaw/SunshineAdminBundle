import { FETCH_CRUD_LIST } from '../actions/action_crud_list.jsx';

export default function(state = null, action) {

    switch (action.type) {
        case FETCH_CRUD_LIST:
            return action.payload.data;
    }
    return state;
}