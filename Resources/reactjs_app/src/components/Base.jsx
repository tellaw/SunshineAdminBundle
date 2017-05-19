import React from 'react';
import { Router, Route } from 'react-router'
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import Header from './Header.jsx';

import Page from './../containers/Page.jsx';
import NotFound from './../components/NotFound.jsx';

export default class Base extends React.Component {

    render() {
        return (
            <div>
                <div className="page-header navbar navbar-fixed-top"><Header/></div>
                <div className="clearfix"> </div>
                <div className="page-container">
                    <Router history={history}>
                        <div>
                            <Route path="*/app/page/demoPageX" component={NotFound}/>
                            <Route path="*/app/page/:pageId/:entity?/:editMode?/:targetId?" component={Page}/>
                        </div>
                    </Router>

                </div>
            </div>
        );
    }
}
