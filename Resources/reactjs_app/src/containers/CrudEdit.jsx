import React from 'react';
import QueryString from 'query-string';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

class CrudEdit extends React.Component {

    componentWillMount() {

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        if ( queryString.targetId != undefined ) {
            // Run Ajax request
        }

    }

    render()
    {

        return (
            <div>Edit Mode!</div>
        );
    }

}


export default connect(null, null)(CrudEdit);