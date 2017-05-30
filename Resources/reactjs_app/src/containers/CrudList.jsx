import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../actions/action_crud_list.jsx';

import CrudListHead from './crud_list/CrudListHead.jsx';
import CrudListBody from './crud_list/CrudListBody.jsx';
import CrudListSearch from './crud_list/CrudListSearch.jsx';
import CrudListPagination from './crud_list/CrudListPagination.jsx';

class CrudList extends React.Component {

    render()
    {
        return(
            <div className="portlet light portlet-fit portlet-datatable bordered">
                <div className="portlet-title">
                    <div className="caption">
                        <i className="icon-settings font-dark"></i>
                        <span className="caption-subject font-dark sbold uppercase">Ajax Datatable</span>
                    </div>
                    <div className="actions">
                        <div className="btn-group btn-group-devided" data-toggle="buttons">
                            <label className="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" className="toggle" id="option1" />Actions</label>
                            <label className="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" className="toggle" id="option2" />Settings</label>
                        </div>
                        <div className="btn-group">
                            <a className="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i className="fa fa-share"></i>
                                <span className="hidden-xs"> Tools </span>
                                <i className="fa fa-angle-down"></i>
                            </a>
                            <ul className="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;"> Export to Excel </a>
                                </li>
                                <li>
                                    <a href="javascript:;"> Export to CSV </a>
                                </li>
                                <li>
                                    <a href="javascript:;"> Export to XML </a>
                                </li>
                                <li className="divider"> </li>
                                <li>
                                    <a href="javascript:;"> Print Invoices </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div className="portlet-body">
                    <div className="table-container">
                        <div className="col-md-8 col-sm-12">
                            <CrudListPagination context={this.props.crudList} />
                        </div>
                        <CrudListSearch />
                        <div className="table-actions-wrapper">
                            <span> </span>
                            <select className="table-group-action-input form-control input-inline input-small input-sm">
                                <option value="">Select...</option>
                                <option value="Cancel">Cancel</option>
                                <option value="Cancel">Hold</option>
                                <option value="Cancel">On Hold</option>
                                <option value="Close">Close</option>
                            </select>
                            <button className="btn btn-sm green table-group-action-submit">
                                <i className="fa fa-check"></i> Submit</button>
                        </div>
                        <table className="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
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
