import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'

class SearchBox extends React.Component {

    render() {

        return (

            <div className="portlet light bordered">
                <div className="portlet-title">
                    <div className="caption">
                        <i className="fa fa-gift"></i> Recherche </div>
                </div>
                <div className="portlet-body form">
                    <form role="form">
                            <div>
                                <div className="input-group">
                                    <input type="text" className="form-control"/>
                                    <span className="input-group-btn">
                                        <button className="btn blue" type="button">Go!</button>
                                    </span>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        );
    }

}

function mapStateToProps({ crudList }) {
    return { crudList };
}

export default connect(mapStateToProps, null)(SearchBox);