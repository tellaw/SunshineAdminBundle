import React from 'react';
import classNames from 'classnames';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import DemoWidget from '../../components/widgets/DemoWidget.jsx';
import CrudList from '../CrudList.jsx';
import CrudEdit from '../CrudEdit.jsx';

class Widget extends React.Component {

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
            if (this.props.context.mode == "1") {
                return <CrudEdit index={this.props.index} widget={this.props.widget} query={this.props.query} />
            } else {
                return <CrudList index={this.props.index} widget={this.props.widget} query={this.props.query} />
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

function mapStateToProps({ context }) {
    return { context };
}

export default connect(mapStateToProps, null)(Widget);