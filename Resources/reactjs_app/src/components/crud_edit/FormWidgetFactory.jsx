import React from 'react';
import classNames from 'classnames';
import QueryString from 'query-string';

import StringField from '../data_types/string/StringField.jsx';

export default class FormWidgetFactory extends React.Component {

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
    getWidgetByType ( element ) {

        let entityName = element[0];
        let entityConfiguration = element[1];

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        switch (entityConfiguration.type) {

            case "string":
                return (
                            <StringField
                                configuration={entityConfiguration}
                                name={entityName}
                                data={this.props.data} />
                );

            case "integer":
                return (
                            <StringField
                                configuration={entityConfiguration}
                                name={entityName}
                                data={this.props.data} />
                );

        }

    }

    getPlaceHolder ( element ) {
        
    }

    render() {
        return (
            <div>
                <label className="col-md-2 control-label" >{this.props.item[1].label}</label>
                <div className="col-md-10">
                    {this.getWidgetByType( this.props.item )}
                    <div className="form-control-focus"> </div>
                </div>
            </div>
        );

    }

}