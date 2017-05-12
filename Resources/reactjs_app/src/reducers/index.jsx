import { combineReducers } from 'redux';
import PageReducer from './reducer_page.jsx';
import CrudListReducer from './reducer_crud_list.jsx';

const rootReducer = combineReducers({
    currentPage: PageReducer,
    crudList: CrudListReducer

});

export default rootReducer;