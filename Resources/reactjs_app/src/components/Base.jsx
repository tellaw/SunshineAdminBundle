import React from 'react';
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
                    <Page/>
                </div>
            </div>
        );
    }
}