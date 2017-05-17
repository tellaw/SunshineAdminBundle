import { combineReducers } from 'redux';
import PageReducer from './reducer_page.jsx';
import CrudListReducer from './reducer_crud_list.jsx';
import CrudEditReducer from './reducer_crud_edit.jsx';
import MenuReducer from './reducer_menu.jsx';

const rootReducer = combineReducers({
    currentPage: PageReducer,
    crudList: CrudListReducer,
    crudEdit: CrudEditReducer,
    menu: MenuReducer

});

export default rootReducer;