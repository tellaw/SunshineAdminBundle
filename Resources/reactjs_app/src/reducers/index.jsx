import { combineReducers } from 'redux';
import PageReducer from './reducer_page.jsx';

const rootReducer = combineReducers({
    currentPage: PageReducer

});

export default rootReducer;