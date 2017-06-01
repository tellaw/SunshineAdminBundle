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
        this.$node = $(this.refs.datetimepicker);
        this.$node.datetimepicker( {
                format: "dd/mm/yyyy - hh:ii:ss",
                language: "fr"
            }
        );
        var myDate = new Date(this.props.value);
        this.setState (
            {
                value: dateFormat( myDate, "dd/mm/yyyy - hh:MM:ss")
            }
        );
    }

    componentWillUnmount() {
        // Clean up the mess when the component unmounts
        this.$node.datetimepicker('destroy');
    }

    render() {
        return (
            <div className="col-md-12" id="defaultrange">
                <div className="input-group date form_datetime bs-datetime">
                    <input type="text" ref="datetimepicker" readOnly="true" className="form-control" name={this.props.name} id={this.props.name} value={this.state.value}/>
                </div>
            </div>
        );
    }

}
