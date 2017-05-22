import React from 'react';
import classNames from 'classnames';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import StringField from '../../components/string/StringField.jsx';

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
console.log (this.props.crudEdit.object[itemName]);
        switch (item.type) {

            case "string":
                return (
                            <StringField
                                configuration={item}
                                name={itemName}
                                value={this.props.crudEdit.object[itemName]}/>
                );

            case "integer":
                return (
                            <StringField
                                configuration={item}
                                name={itemName}
                                value={this.props.crudEdit.object[itemName]}/>
                );

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