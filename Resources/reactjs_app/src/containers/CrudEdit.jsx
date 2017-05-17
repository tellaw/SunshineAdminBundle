import React from 'react';
import serialize from 'form-serialize';
import QueryString from 'query-string';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchId } from '../actions/action_crud_edit.jsx';
import { postForm } from '../actions/action_crud_edit.jsx';

import FormWidgetFactory from '../components/crud_edit/FormWidgetFactory.jsx';

class CrudEdit extends React.Component {

    constructor(props) {
        super(props);
        this.state = {isToggleOn: true};

        // This binding is necessary to make `this` work in the callback
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
    }

    componentWillMount() {

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        if ( queryString.targetId != undefined ) {
            // Run Ajax request
            this.props.fetchId(queryString.entity, queryString.targetId);
        }

    }

    handleFormSubmit (event) {
        event.preventDefault();

        var queryString = QueryString.parse(location.search) ;

        var form = document.querySelector('#formCrudObj');
        var obj = serialize(form, { hash: true });

        this.props.postForm( queryString.entity, queryString.targetId, obj )
        .then(function(response) {
            console.log("Response axios : ",response);
        }) .catch(function (error) {
            console.log(error);
        });

        //console.log (response);
    }

    render()
    {

        if (this.props.crudEdit == undefined) { return <div></div> }

        return (
            <div className="col-md-12">
                <div className="portlet light bordered">
                    <form id="formCrudObj" className="form-horizontal">
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
                                    <button type="submit" className="btn blue" onClick={this.handleFormSubmit}>Submit</button>
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
    return bindActionCreators({ fetchId, postForm }, dispatch);
}

function mapStateToProps({ crudEdit }) {
    return { crudEdit };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudEdit);
