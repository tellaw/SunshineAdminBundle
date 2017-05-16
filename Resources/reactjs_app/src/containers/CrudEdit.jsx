import React from 'react';
import QueryString from 'query-string';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchId } from '../actions/action_crud_edit.jsx';

import FormWidgetFactory from '../components/crud_edit/FormWidgetFactory.jsx';

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
            <div className="col-md-12">
                <div className="portlet light bordered">
                    <form className="form-horizontal">
                        {Object.entries(this.props.crudEdit.headers).map((item, index) => {
                            return (
                                <div className="form-group form-md-line-input" key={index}>
                                <FormWidgetFactory headers={this.props.crudEdit.headers} data={this.props.crudEdit.object} index={index} item={item} />
                                </div>
                            )
                        })}

                        <div className="form-actions">
                            <div className="row">
                                <div className="col-md-offset-2 col-md-10">
                                    <button type="submit" className="btn blue">Submit</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
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
