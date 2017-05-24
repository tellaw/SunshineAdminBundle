import React from 'react';

export default class DatetimeField extends React.Component {

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};
    }

    componentDidMount() {
        console.log ("DateTimePicker",$(this.refs.datetimepicker));
        this.$node = $(this.refs.datetimepicker);

        this.$node.datetimepicker(

        );

    }

    componentWillUnmount() {
        // Clean up the mess when the component unmounts
        this.$node.datetimepicker('destroy');
    }

    render() {

        return (
            <div className="col-md-10" id="defaultrange">
                <div className="input-group date form_datetime bs-datetime">
                    <input type="text" ref="datetimepicker" readOnly="true" className="form-control" name={this.props.name} id={this.props.name} value="15/05/2017"/>
                </div>
            </div>

        );
    }

}
