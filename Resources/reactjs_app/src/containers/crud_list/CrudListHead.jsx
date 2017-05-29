import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'

class CrudListHead extends React.Component {

    render() {

        if (this.props.crudList == null) {return (<thead><tr><td></td></tr></thead>)}

        return (
            <thead>
            <tr role="row" className="heading">
                <th width="2%">
                    <label className="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                        <input type="checkbox" className="group-checkable" data-set="#sample_2 .checkboxes" />
                        <span></span>
                    </label>
                </th>
                {Object.entries(this.props.crudList.headers).map((item, index) => {
                    return (<th key={index}>{item[1].label}</th>)
                })}
            </tr>
            <tr role="row" className="filter">
                <td> </td>
                {Object.entries(this.props.crudList.headers).map((item, index) => {
                    return (<td key={index}><input type="text" className="form-control form-filter input-sm" name={item[0]} /></td>)
                })}
            </tr>
            </thead>
        );
    }

}


function mapStateToProps({ crudList }) {
    return { crudList };
}

export default connect(mapStateToProps, null)(CrudListHead);
