import React from 'react';
import { Link } from 'react-router-dom'
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import QueryString from 'query-string';

import { fetchId } from '../../actions/action_crud_edit.jsx';
import { contextUpdate } from '../../actions/action_context.jsx';

import StringView from '../../components/data_types/string/StringView.jsx';

class CrudListBody extends React.Component {

    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }

    getItemRenderByType ( type, data ) {
        switch (type) {
            case "string":
                return <StringView data={data} />;
            case "integer":
                return <StringView data={data} />;
        }

    }

    handleClick ( itemId ) {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.context.entityName;
        var targetId    = itemId;
        var pageId      = this.props.context.pageId;
        var editMode    = "1";

        this.props.fetchId(entityName, targetId);
        this.props.contextUpdate ( entityName, targetId, editMode, pageId );

        var currentLocation = basePath + pageId +"/" + entityName + "/1/" + targetId;
        history.push(currentLocation);
    }

    render() {
        return (
            <tbody>
            {this.props.list.map((item, index) => {
                return (<tr key={index}>
                    {Object.entries(item).map((item, index) => {
                        if (index != 0) {
                            return  (
                                        <td key={index}>{this.getItemRenderByType( this.props.headers[item[0]].type, item[1]  )}</td>
                                    )
                        } else {
                            return  (
                                <td key={index}><a onClick={this.handleClick.bind(this, item[1])}>{this.getItemRenderByType( this.props.headers[item[0]].type, item[1]  )}</a></td>
                            )
                        }
                    })}
                </tr>)
            })}
            </tbody>
        );
    }

}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchId, contextUpdate }, dispatch);
}

function mapStateToProps({ context }) {
    return { context };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudListBody);