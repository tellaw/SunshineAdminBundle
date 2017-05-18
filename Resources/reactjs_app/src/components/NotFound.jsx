import React, { Component } from 'react';

import Sidebar from '../containers/Sidebar.jsx';


export default class NotFound extends React.Component {


    render() {

        return (
            <div>
                <Sidebar/>
                <div className="page-content-wrapper">

                    <div className="page-content">
NOT FOUND
                    </div>
                </div>
            </div>
        );
    }
}
