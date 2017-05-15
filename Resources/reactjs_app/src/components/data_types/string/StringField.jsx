import React from 'react';

export default class StringView extends React.Component {

    // This class receive tow parameters :
    // 1 : element -> Json Item as Element
    // 2 : name -> This is the object name (json id)
    // 3 ; data (full) -> The data object

    render() {
        return (<input type="text" className="form-control" id="form_control_1" placeholder="Enter your name" value={this.props.data[this.props.name]} />);
    }

}