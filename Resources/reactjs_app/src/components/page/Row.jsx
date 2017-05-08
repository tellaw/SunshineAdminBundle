import React from 'react';
import Widget from './Widget.jsx';

export default class Row extends React.Component {

    render() {
        return (
            <div className="row">
                {this.props.row.widgets.map((widget, index) => {
                    return <Widget key={index} widget={widget} />
                })}
            </div>
        );
    }

}