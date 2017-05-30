import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../../actions/action_crud_list.jsx';

class CrudListPagination extends React.Component {

    constructor(props) {
        super(props);
        this.handleFilter = this.handleFilter.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.state = {
            value: '',
            totalCount: 0,
            page: 0
        };
        //console.log(this.props.crudList.context);
    }

    componentWillMount(){
        console.log(this.props.crudList);
        if (this.props.crudList == null) {
            return;
        }

        if (typeof this.props.crudList.context == 'undefined') {
            return;
        }

        console.log(this.props.crudList.context);
        this.setState({
            totalCount: this.props.crudList.context.totalCount,
            limit: this.props.crudList.context.nbItemPerPage,
            page: this.props.crudList.context.startPage
        });
    }

    handleFilter() {
        console.log(this.props.context);
        console.log(this.props.crudList.context);
        var entityName = this.props.crudList.context.entityName;
        var filterParam = 'filters['+this.props.item[0]+']='+this.state.value;
        this.props.fetchList(entityName, 0, 10, '', filterParam);
    }

    handleChange ( event ) {
        this.setState({value: event.target.value});
    }

    render() {

        if (this.props.crudList == null) {return (<div/>)}

        if (this.state.totalCount <= this.state.limit) {
            return (
                <div className="dataTables_paginate paging_bootstrap_extended" id="datatable_ajax_paginate" >
                    <div className="dataTables_info" id="datatable_ajax_info" role="status" aria-live="polite">
                        total {this.state.totalCount} éléments
                    </div>
                </div>
            )
        }

        return (
            <div className="dataTables_paginate paging_bootstrap_extended" id="datatable_ajax_paginate">
                <div className="pagination-panel"> Page <a href="#" className="btn btn-sm default prev">
                    <i className="fa fa-angle-left"></i></a>
                    <input type="text"
                           className="pagination-panel-input form-control input-sm input-inline input-mini"
                           maxLength="5"
                           value=""
                           style={{textAlign:"center", margin: "0 5px"}} />
                    <a href="#" className="btn btn-sm default next disabled"><i className="fa fa-angle-right"></i></a> of <span className="pagination-panel-total">2</span>
                </div>
                <div className="dataTables_info" id="datatable_ajax_info" role="status" aria-live="polite">
                    <span className="seperator">|</span>total {this.state.totalCount} éléments
                </div>
            </div>
        );
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchList }, dispatch);
}

function mapStateToProps({ context, crudList }) {
    return { context, crudList };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudListPagination);
