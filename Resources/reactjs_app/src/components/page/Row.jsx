import React from 'react';
import Widget from './../../containers/page/Widget.jsx';

export default class Row extends React.Component {

    render() {
        return (
            <div className="row">
                {this.props.row.widgets.map((widget, index) => {
                    return <Widget key={this.props.uniqKey +"-" + index} widget={widget}/>
                })}
            </div>
        );
    }

}