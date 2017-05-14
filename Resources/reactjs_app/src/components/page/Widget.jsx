import React from 'react';
import classNames from 'classnames';
import QueryString from 'query-string';

import DemoWidget from '../widgets/DemoWidget.jsx';
import CrudList from '../../containers/CrudList.jsx';
import CrudEdit from '../../containers/CrudEdit.jsx';

export default class Widget extends React.Component {

    getClasses () {
        var size = this.props.widget.columns;
        if (!size) {size = "3";}
        var classnames = [
            "col-lg-"+size,
            "col-md-"+size,
            "col-sm-"+size,
            "col-xs-"+size
        ];

        return classNames(classnames);
    }

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
            <div className={this.getClasses()}>
                {this.getWidgetByType()}
            </div>
        );
    }

}