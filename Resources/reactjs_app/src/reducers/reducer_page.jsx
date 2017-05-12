import { FETCH_PAGE } from '../actions/index.jsx';

export default function(state = null, action) {

    console.log (action);

    switch (action.type) {
        case FETCH_PAGE:
            return action.payload.data;
    }
    return state;
}