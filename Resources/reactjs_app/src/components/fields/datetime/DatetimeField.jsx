import React from 'react';

export default class DatetimeField extends React.Component {

    constructor(props) {
        super(props);

        this.state = {value: this.props.value};
    }

    render() {

        return (
            <div className="input-group" id="defaultrange">
                <input type="text" className="form-control" name={this.props.name} id={this.props.name} value={this.state.value} />
                <span className="input-group-btn">
                    <button className="btn default date-range-toggle" type="button">
                        <i className="fa fa-calendar"></i>
                    </button>
                </span>
            </div>

        );
    }

}
