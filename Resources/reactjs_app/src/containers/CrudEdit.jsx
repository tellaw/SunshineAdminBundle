import React from 'react';
import QueryString from 'query-string';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchId } from '../actions/action_crud_edit.jsx';

import { FormWidgetFactory } from '../components/crud_edit/FormWidgetFactory.jsx';

class CrudEdit extends React.Component {

    componentWillMount() {

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        if ( queryString.targetId != undefined ) {
            console.log ("Target ID : ", queryString.targetId);
            // Run Ajax request
            this.props.fetchId(queryString.entity, queryString.targetId);
        }

    }

    render()
    {

        if (this.props.crudEdit == undefined) { return <div></div> }

        return (
            <div>
                <form>
                    {Object.entries(this.props.crudEdit.headers).map((item, index) => {
                        return (<th key={index}>{item[0]} - {item[1].label}</th>)
                    })}
                </form>
            </div>

        );
    }

}


function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchId }, dispatch);
}

function mapStateToProps({ crudEdit }) {
    return { crudEdit };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudEdit);
