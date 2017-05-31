import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'

import CrudListFilter from './CrudListFilter.jsx';

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
                    <th></th>
                </tr>
                <tr role="row" className="filter">
                    <td> </td>
                    {Object.entries(this.props.crudList.headers).map((item, index) => {
                        return (
                            <td key={index}><CrudListFilter item={item} context={this.props.crudList.context} /></td>
                        )
                    })}
                    <td></td>
                </tr>
            </thead>
        );
    }
}

function mapStateToProps({ crudList }) {
    return { crudList };
}

export default connect(mapStateToProps, null)(CrudListHead);
