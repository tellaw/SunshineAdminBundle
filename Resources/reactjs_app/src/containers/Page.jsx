import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import Row from './../components/page/Row.jsx';
import Sidebar from '../containers/Sidebar.jsx';

import { fetchPage } from '../actions/action_page.jsx';
import { contextUpdate } from '../actions/action_context.jsx';

class Page extends React.Component {

    componentWillMount() {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.match.params.entity;
        var targetId    = this.props.match.params.targetId;
        var mode        = this.props.match.params.editMode;
        var pageId      = this.props.match.params.pageId;

        this.props.contextUpdate ( entityName, targetId, mode, pageId );

        this.props.fetchPage( pageId );

    }

    render() {

        if (this.props.currentPage == null) {return (<div></div>);}

        return (
            <div>
                <Sidebar/>
                <div className="page-content-wrapper">

                    <div className="page-content">

                        <h1 className="page-title" id="reactContent"> {this.props.currentPage.title}
                            <small>{this.props.currentPage.description}</small>
                        </h1>

                        {this.props.currentPage.rows.map((row, index) => {
                            return <Row key={index} row={row} query={this.props.match.params}/>
                        })}

                    </div>
                </div>
            </div>
        );
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchPage, contextUpdate }, dispatch);
}

function mapStateToProps({ currentPage }) {
    return { currentPage };
}

export default connect(mapStateToProps, mapDispatchToProps)(Page);
