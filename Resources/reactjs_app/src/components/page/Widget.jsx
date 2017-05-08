import React from 'react';
import classNames from 'classnames';

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

    getWidgetByType () {
        if (this.props.widget.type == "html") {
            // Render HTML Widget
        }
    }

    render() {
        return (
            <div className={this.getClasses()}>
                {this.getWidgetByType()}
                <p>This is a widget</p>
            </div>
        );
    }

}