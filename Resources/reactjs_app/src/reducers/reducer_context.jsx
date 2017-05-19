import { CONTEXT_UPDATE } from '../actions/action_context.jsx';

export default function(state = null, action) {

    console.log ("Action : ", action);

    switch (action.type) {
        case CONTEXT_UPDATE:
            return action.payload;
    }
    return state;
}