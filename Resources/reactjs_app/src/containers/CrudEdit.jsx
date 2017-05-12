import React from 'react';
import QueryString from 'query-string';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../actions/action_crud_list.jsx';

import CrudListHead from '../components/crud_list/CrudListHead.jsx';
import CrudListBody from '../components/crud_list/CrudListBody.jsx';

class CrudList extends React.Component {

    componentWillMount() {

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        this.props.fetchList(queryString.entity)


    }

    render()
    {

        //console.log (QueryString.parse(location.search));

        if (this.props.crudList == null) {return (<div></div>);}


        return (
            <div>Edit Mode!</div>
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