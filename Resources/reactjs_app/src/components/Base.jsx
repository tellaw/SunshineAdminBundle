import React from 'react';
import { Router, Route } from 'react-router'
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import Header from './Header.jsx';
import Menu from './Menu.jsx';
import Page from './Page.jsx';

export default class Base extends React.Component {
    render() {
        return (
            <div>
                <div className="page-header navbar navbar-fixed-top"><Header/></div>
                <div className="clearfix"> </div>
                <div className="page-container">
                    <Menu/>

                    <Router history={history}>
                        <Route path="*/test/mypage/:pageId" component={Page}/>
                    </Router>

                </div>
            </div>
        );
    }
}