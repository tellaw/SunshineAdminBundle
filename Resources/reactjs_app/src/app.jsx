import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxPromise from 'redux-promise';
import combineActionsMiddleware from 'redux-combine-actions';

import reducers from './reducers/index.jsx';
import Base from './components/Base.jsx';

const middleware = [ ReduxPromise, combineActionsMiddleware  ];
const store = createStore(reducers, applyMiddleware(...middleware));

ReactDOM.render(
    <Provider store={store}>
        <Base />
    </Provider>,
    document.querySelector('#react-app')
);
