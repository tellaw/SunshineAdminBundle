import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../actions/action_crud_list.jsx';

import CrudListHead from './crud_list/CrudListHead.jsx';
import CrudListBody from './crud_list/CrudListBody.jsx';

class CrudList extends React.Component {

    render()
    {

        return (
            <div className="portlet box red">
                <div className="portlet-title">
                    <div className="caption">
                        <i className="fa fa-picture"></i>{this.props.widget.title}</div>
                    <div className="tools">
                        <a href="javascript:;" className="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" className="config" data-original-title="" title=""> </a>
                        <a href="javascript:;" className="reload" data-original-title="" title=""> </a>
                        <a href="javascript:;" className="remove" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div className="portlet-body">
                    <div className="table-scrollable">
                        <table className="table table-condensed table-hover">
                            <CrudListHead />
                            <CrudListBody />
                        </table>
                    </div>
                </div>
            </div>
        );
    }

}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchList }, dispatch);
}

function mapStateToProps({ crudList }) {
    return { crudList };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudList);