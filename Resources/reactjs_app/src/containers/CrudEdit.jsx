import React from 'react';
import serialize from 'form-serialize';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import MDSpinner from "react-md-spinner";

import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import { postForm } from '../actions/action_crud_edit.jsx';
import { contextUpdate } from '../actions/action_context.jsx';

import FormWidgetFactory from './crud_edit/FormWidgetFactory.jsx';

class CrudEdit extends React.Component {

    constructor(props) {
        super(props);

        // This binding is necessary to make `this` work in the callback
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
        this.handlerReturnToList = this.handlerReturnToList.bind(this);
    }

    handleFormSubmit (event) {
        event.preventDefault();

        var form = document.querySelector('#formCrudObj');
        var obj = serialize(form, { hash: true });

        this.props.postForm( this.props.query.entity, this.props.query.targetId, obj )
        .then(function(response) {
            console.log("Response axios : ",response);

        }) .catch(function (error) {
            console.log(error);
        });

    }

    handlerReturnToList ( e ) {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.context.entityName;
        var targetId    = this.props.context.targetId;
        var pageId      = this.props.context.pageId;

        this.props.contextUpdate ( entityName, targetId, "0", pageId );

        var currentLocation = basePath + pageId +"/" +entityName+"/0/"+targetId;
        history.push(currentLocation);
    }

    render()
    {

        if (this.props.crudEdit == null || this.props.crudEdit.object == null) { return (<div><MDSpinner /></div>) }
        console.log ( "Rendering", this.props.crudEdit.object );

        return (

            <div className="col-md-12">

                <div className="portlet light bordered">
                    <form id="formCrudObj" className="form-horizontal">

                        {Object.entries(this.props.crudEdit.headers).map((item, index) => {
                            var uniqId = this.props.context.targetId + "-" + item[0];
                            return (
                                <div className="form-group form-md-line-input" key={uniqId}>
                                    <FormWidgetFactory uniqKey={uniqId} itemName={item[0]} />
                                </div>
                            )
                        })}

                        <div className="form-actions">
                            <div className="row">
                                <div className="col-md-offset-2 col-md-10">
                                    <button type="submit" onClick={this.handleFormSubmit} className="btn btn-primary mt-ladda-btn ladda-button" data-style="zoom-in">
                                        <span className="ladda-label">
                                        <i className="icon-magnifier"></i> Enregistrer</span>
                                        <span className="ladda-spinner"></span></button>
                                        <span style={{ marginLeft: '15px' }}><a onClick={this.handlerReturnToList}>Retour à la liste</a></span>
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
    return bindActionCreators({ postForm, contextUpdate }, dispatch);
}

function mapStateToProps({ crudEdit, context }) {
    return { crudEdit, context };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudEdit);
