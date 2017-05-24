import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'

class CrudListHead extends React.Component {

    render() {

        if (this.props.crudList == null) {return (<thead><tr><td></td></tr></thead>)}

        return (
            <thead>
            <tr>
                {Object.entries(this.props.crudList.headers).map((item, index) => {
                    return (<th key={index}>{item[1].label}</th>)
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