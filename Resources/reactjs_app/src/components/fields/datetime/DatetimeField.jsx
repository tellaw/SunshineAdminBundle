import React from 'react';
import dateFormat  from 'dateformat';

export default class DatetimeField extends React.Component {

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};
    }

    // Conf Sample
    // https://uxsolutions.github.io/bootstrap-datepicker/?markup=input&format=dd%2Fmm%2Fyyyy&weekStart=&startDate=&endDate=&startView=0&minViewMode=0&maxViewMode=4&todayBtn=false&clearBtn=false&language=fr&orientation=auto&multidate=&multidateSeparator=&keyboardNavigation=on&forceParse=on#sandbox
    componentDidMount() {
        console.log ("DateTimePicker",$(this.refs.datetimepicker));
        this.$node = $(this.refs.datetimepicker);

        this.$node.datetimepicker( {
            format: "dd/mm/yyyy-hh:ii:ss",

            inline: true,
            sideBySide: true
            }
        );
        var myDate = new Date(this.props.value);
        console.log (dateFormat( myDate, "dd/mm/yyyy-hh:MM:ss"));
        //this.$node.val("15/05/2017");

    }

    componentWillUnmount() {
        // Clean up the mess when the component unmounts
        this.$node.datetimepicker('destroy');
    }

    render() {

        return (
            <div className="col-md-10" id="defaultrange">
                <div className="input-group date form_datetime bs-datetime">
                    {this.props.value}
                    <input type="text" ref="datetimepicker" readOnly="true" className="form-control" name={this.props.name} id={this.props.name} value="15/05/2017-16:25:10"/>
                </div>
            </div>

        );
    }

}
