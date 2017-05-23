import React from 'react';

export default class RelatedObjectField extends React.Component {

    constructor(props) {
        super(props);
        this.state = {value: this.props.value};
    }

    render() {

        return (
            <input
                readOnly="readOnly"
                type="text"
                className="form-control"
                name={this.props.name}
                id={this.props.name}
                placeholder="Enter your name"
                value={this.state.value}/>
        );
    }

}
