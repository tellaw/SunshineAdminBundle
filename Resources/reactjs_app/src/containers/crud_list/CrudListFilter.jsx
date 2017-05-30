import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchList } from '../../actions/action_crud_list.jsx';

class CrudListFilter extends React.Component {

    constructor(props) {
        super(props);
        this.handleFilter = this.handleFilter.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.state = {value: ''};
    }

    handleFilter() {
        console.log(this.props.context);
        console.log(this.props.crudList.context);
        var entityName = this.props.crudList.context.entityName;
        var filterParam = 'filters['+this.props.item[0]+']='+this.state.value;
        this.props.fetchList(entityName, 0, 10, '', filterParam);
    }

    handleChange ( event ) {
        this.setState({value: event.target.value});
    }

    render() {
        return (
            <div>
                <input type="text" className="form-control form-filter input-sm" value={this.state.value} onChange={this.handleChange} />
                <button onClick={this.handleFilter}>OK</button>
            </div>
        );
    }

}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchList }, dispatch);
}

function mapStateToProps({ context, crudList }) {
    return { context, crudList };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudListFilter);
