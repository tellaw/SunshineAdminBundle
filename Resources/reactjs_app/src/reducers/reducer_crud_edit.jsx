import { FETCH_CRUD_EDIT } from '../actions/action_crud_edit.jsx';

export default function(state = null, action) {

    switch (action.type) {
        case FETCH_CRUD_EDIT:
            return action.payload.data;
    }
    return state;
}