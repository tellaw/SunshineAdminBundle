import React from 'react';
import classNames from 'classnames';
import QueryString from 'query-string';

import StringField from '../data_types/string/StringField.jsx';

export default class FormWidgetFactory extends React.Component {

    // This method is the Widget Factory
    getWidgetByType ( element ) {

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        if (element[1].type == "string") {
            return <StringField element={element[1]} name={element[0]} data={this.props.data} />
        }

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