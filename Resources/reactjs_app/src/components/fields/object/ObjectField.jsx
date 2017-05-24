import React from "react";

export default class StringField extends React.Component {

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};
        this.handleChange = this.handleChange.bind(this);
    }

    handleChange(event) {
        this.setState({value: event.target.value});
    }

    componentDidMount() {
        this.$node = $(this.refs.selectpicker);
        this.$node.selectpicker({
            size: 10
        });
    }


    render() {
        return (
            <select ref="selectpicker" className="selectpicker" data-live-search="true">
                <option value={this.props.name}>{this.props.name}</option>
                <option value="None">None</option>
            </select>
        );
    }
}
