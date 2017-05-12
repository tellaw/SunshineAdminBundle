import React from 'react';
import classNames from 'classnames';

import DemoWidget from '../widgets/DemoWidget.jsx';
import CrudList from '../widgets/CrudList.jsx';

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

        if ( widgetType == "demo") {
            return <DemoWidget index={this.props.index} widget={this.props.widget} />
        } else if ( widgetType == "crudList") {
            return <CrudList index={this.props.index} widget={this.props.widget} />
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