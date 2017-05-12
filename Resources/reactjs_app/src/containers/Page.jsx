import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import Row from './../components/page/Row.jsx';

import { fetchPage } from '../actions/index.jsx';

class Page extends React.Component {

    componentWillMount() {
        var pageId = this.props.match.params.pageId;
        this.props.fetchPage( pageId );
    }

    render() {

        if (this.props.currentPage == null) {
            console.log ("page is null");
            return (<div></div>);
        } else {
            console.log ("page is not null anymore", this.props.currentPage);
        }

        return (
            <div className="page-content-wrapper">
                <div className="page-content">

                    <h1 className="page-title" id="reactContent"> {this.props.currentPage.title}
                        <small>{this.props.currentPage.description}</small>
                    </h1>

                    {this.props.currentPage.rows.map((row, index) => {
                        return <Row key={index} row={row} />
                    })}

                </div>
            </div>
        );
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchPage }, dispatch);
}

function mapStateToProps({ currentPage }) {
    return { currentPage };
}

export default connect(mapStateToProps, mapDispatchToProps)(Page);