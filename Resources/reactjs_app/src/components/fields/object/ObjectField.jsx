import React from 'react';

export default class StringField extends React.Component {

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
            <select className="selectpicker">
                <option>Mustard</option>
                <option>Ketchup</option>
                <option>Relish</option>
            </select>
        );
    }

}
