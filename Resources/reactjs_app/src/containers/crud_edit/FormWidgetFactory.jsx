import React from 'react';
import classNames from 'classnames';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import StringField from '../../components/fields/string/StringField.jsx';
import ReadonlyField from '../../components/fields/readonly/ReadonlyField.jsx';
import HiddenField from '../../components/fields/hidden/HiddenField.jsx';
import DatetimeField from '../../components/fields/datetime/DatetimeField.jsx';

class FormWidgetFactory extends React.Component {

    /**
     *
     * @param element is the object containing the configuraition :
     *
     * (2) ["name",
     *          Object]0:
     *              "name"1: Object
     *              label: "form-label"
     *              placeholder: "xxx"
     *              readOnly: false
     *              sortable: true
     *              type: "string"
     *
     * element[0] -> Name of the attribute
     * element[1] -> Is the object with te configuration of the attribute for the form object
     *
     * this.props.data -> is the data object with the values of the entity
     *
     */

    // This method is the Widget Factory
    getWidgetByType ( itemName, item ) {

        console.log ("Field Type :", item.type);

        switch (item.type) {

            case "string":
                return (<StringField name={itemName} value={this.props.crudEdit.object[itemName]}/>);

            case "integer":
                return (<StringField name={itemName} value={this.props.crudEdit.object[itemName]}/>);

            case "readonly":
                return (<ReadonlyField name={itemName} value={this.props.crudEdit.object[itemName]}/>);

            case "hidden":
                return (<HiddenField name={itemName} value={this.props.crudEdit.object[itemName]}/>);

            case "datetime":
                return (<DatetimeField name={itemName} value={this.props.crudEdit.object[itemName]}/>);
        }

    }

    getPlaceHolder ( element ) {
        
    }

    render() {

        var uniqKey = this.props.uniqKey;
        var itemName = this.props.itemName;

        if (this.props.crudEdit == null) {return <div></div>}
        console.log (this.props.crudEdit);

        var item = this.props.crudEdit.headers[this.props.itemName];

        return (
            <div>
                <label className="col-md-2 control-label" >{item.label}</label>
                <div className="col-md-10">
                    {this.getWidgetByType( itemName, item )}
                    <div className="form-control-focus"> </div>
                </div>
            </div>
        );

    }

}

function mapStateToProps({ crudEdit, context }) {
    return { crudEdit, context };
}

export default connect(mapStateToProps, null)(FormWidgetFactory);