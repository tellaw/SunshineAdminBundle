import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import Row from './../components/page/Row.jsx';
import Sidebar from '../containers/Sidebar.jsx';

import { fetchPage } from '../actions/action_page.jsx';
import { fetchCrudEdit } from '../actions/action_crud_edit.jsx';
import { fetchList } from '../actions/action_crud_list.jsx';
import { contextUpdate } from '../actions/action_context.jsx';

class Page extends React.Component {

    componentWillMount() {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.match.params.entity;
        var targetId    = this.props.match.params.targetId;
        var mode        = this.props.match.params.editMode;
        var pageId      = this.props.match.params.pageId;

        // update the context
        this.props.contextUpdate ( entityName, targetId, mode, pageId );

        // Update the current Page
        this.props.fetchPage( pageId );

        this.props.fetchCrudEdit( entityName, targetId );

        this.props.fetchList ( entityName );


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
                            var key = this.props.match.params.pageId+"-"+index;
                            return <Row key={key} row={row} uniqKey={key} />
                        })}

                    </div>
                </div>
            </div>
        );
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchPage, contextUpdate, fetchList, fetchCrudEdit }, dispatch);
}

function mapStateToProps({ currentPage }) {
    return { currentPage };
}

export default connect(mapStateToProps, mapDispatchToProps)(Page);
