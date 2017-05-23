import React from 'react';

export default class HiddenField extends React.Component {

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};

    }

    render() {

        return (
            <input
                type="hidden"
                name={this.props.name}
                id={this.props.name}
                value={this.state.value}/>
        );
    }

}
