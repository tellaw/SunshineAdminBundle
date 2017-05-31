import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../../actions/action_crud_list.jsx';

class CrudListPagination extends React.Component {

    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.goToPage = this.goToPage.bind(this);
        this.state = {
            page: 1
        };
    }

    componentDidMount(){
        console.log('componentWillMount');
        if (this.props.crudList == null) {
            return;
        }

        if (typeof this.props.crudList.context == 'undefined') {
            return;
        }

        this.setState({
            //totalCount: this.props.crudList.context.pagination.totalCount,
            //limit: this.props.crudList.context.pagination.limit,
            page: Number(this.props.crudList.context.pagination.page),
            //totalPages: Number(this.props.crudList.context.pagination.totalPages),
            nextEnabled: this.state.page < this.props.crudList.context.pagination.totalPages ? '' : 'disabled',
            prevEnabled: this.state.page == 1 ? 'disabled' : ''
        });
    }

    /**
     * Rechargement de la liste si besoin
     *
     * @param prevProps
     * @param prevState
     */
    componentDidUpdate(prevProps, prevState){
        var entityName = this.props.crudList.context.entityName;
        //console.log('filters');
        //console.log(this.props.crudList.context.filters);
        if (prevState.page != this.state.page) {
            this.props.fetchList(entityName, this.state.page, 1, this.props.crudList.context.searchKey, this.props.crudList.context.filters);
        }
    }

    handleChange(event){
        var value = event.target.value;
        if (value > 0 && value <= this.props.crudList.context.pagination.totalPages) {
            this.setState({page: Number(event.target.value)});
        }
    }

    /**
     * Changement de page
     * 
     * @param event
     */
    goToPage(event){
        if ($(event.target).hasClass('next')) {
            let newPage = this.state.page+1;
            this.setState({
                page: newPage,
                nextEnabled: newPage < this.props.crudList.context.pagination.totalPages ? '' : 'disabled',
                prevEnabled: ''
            });
        } else {
            let newPage = this.state.page-1;
            this.setState({
                page: newPage,
                nextEnabled: '',
                prevEnabled: newPage == 1 ? 'disabled' : ''
            });
        }
    }

    render() {
        if (this.props.crudList == null) {return (<div/>)}
        if (this.props.crudList.context.pagination.totalPages <= 1) {
            return (
                <div className="dataTables_paginate paging_bootstrap_extended" id="datatable_ajax_paginate" >
                    <div className="dataTables_info" id="datatable_ajax_info" role="status" aria-live="polite">
                        total {this.props.crudList.context.pagination.totalCount} éléments
                    </div>
                </div>
            )
        }

        return (
            <div className="col-md-8 col-sm-12">
                <div className="dataTables_paginate paging_bootstrap_extended" id="datatable_ajax_paginate">
                    <div className="pagination-panel"> Page
                        <a href="#" className={'btn btn-sm default prev ' + this.state.prevEnabled} onClick={this.goToPage}>
                        <i className="fa fa-angle-left"></i></a>
                        <input type="text"
                               className="pagination-panel-input form-control input-sm input-inline input-mini"
                               maxLength="5"
                               value={this.state.page}
                               style={{textAlign:"center", margin: "0 5px"}}
                               onChange={this.handleChange}
                               />
                        <a href="#" className={'btn btn-sm default next ' + this.state.nextEnabled} onClick={this.goToPage}>
                            <i className="fa fa-angle-right"></i></a> sur
                            <span className="pagination-panel-total"> {this.props.crudList.context.pagination.totalPages}</span>
                    </div>
                </div>
                <div className="dataTables_info" id="datatable_ajax_info" role="status" aria-live="polite">
                    <span className="seperator">|</span>total {this.props.crudList.context.pagination.totalCount} éléments
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
