import { combineReducers } from 'redux';
import PageReducer from './reducer_page.jsx';
import CrudListReducer from './reducer_crud_list.jsx';
import CrudEditReducer from './reducer_crud_edit.jsx';

const rootReducer = combineReducers({
    currentPage: PageReducer,
    crudList: CrudListReducer,
    crudEdit: CrudEditReducer

});

export default rootReducer;