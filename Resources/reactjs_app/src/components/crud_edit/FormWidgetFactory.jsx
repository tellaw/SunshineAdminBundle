import React from 'react';
import classNames from 'classnames';
import QueryString from 'query-string';


export default class FormWidgetFactory extends React.Component {

    // This method is the Widget Factory
    getWidgetByType () {

        var widgetType = this.props.widget.type;

        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;

        if ( widgetType == "demo") {
            return <DemoWidget index={this.props.index} widget={this.props.widget} />
        } else if ( widgetType == "crudList") {
            if (queryString.editMode != undefined) {
                return <CrudEdit index={this.props.index} widget={this.props.widget} />
            } else {
                return <CrudList index={this.props.index} widget={this.props.widget} />
            }

        }
    }

    render() {
        return (
            <div className="row">
                {this.getWidgetByType()}
            </div>
        );
    }

}