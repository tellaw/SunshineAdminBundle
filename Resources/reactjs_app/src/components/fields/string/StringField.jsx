import React from 'react';

export default class StringField extends React.Component {

    // This class receive tow parameters :
    // 1 : element -> Json Item as Element
    // 2 : name -> This is the object name (json id)

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};

        this.handleChange = this.handleChange.bind(this);
    }

    handleChange ( event ) {
        this.setState({value: event.target.value});
    }

    render() {

        return (
            <input
                type="text"
                className="form-control"
                name={this.props.name}
                id={this.props.name}
                placeholder="Enter your name"
                value={this.state.value}
                onChange={this.handleChange} />
        );
    }

}
