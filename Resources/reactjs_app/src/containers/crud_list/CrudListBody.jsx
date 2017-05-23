import React from 'react';
import { Link } from 'react-router-dom'
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import QueryString from 'query-string';

import { fetchCrudEdit } from '../../actions/action_crud_edit.jsx';
import { resetCrudEdit } from '../../actions/action_crud_edit.jsx';
import { contextUpdate } from '../../actions/action_context.jsx';

import StringView from '../../components/fields/string/StringView.jsx';
import ReadonlyView from '../../components/fields/readonly/ReadonlyView.jsx';
import HiddenView from '../../components/fields/hidden/HiddenView.jsx';
import DatetimeView from '../../components/fields/datetime/DatetimeView.jsx';

class CrudListBody extends React.Component {

    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }

    getItemRenderByType ( type, fieldData ) {
        switch (type) {
            case "string":
                return <StringView data={fieldData} />;
            case "integer":
                return <StringView data={fieldData} />;
            case "readonly":
                return <StringView data={fieldData} />;
            case "hidden":
                return <StringView data={fieldData} />;
            case "datetime":
                return <StringView data={fieldData} />;
        }

    }

    handleClick ( itemId ) {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.context.entityName;
        var targetId    = itemId;
        var pageId      = this.props.context.pageId;
        var editMode    = "1";

        // Fetch content for editing
        this.props.resetCrudEdit();
        this.props.fetchCrudEdit(entityName, targetId);

        // Update Context
        this.props.contextUpdate ( entityName, targetId, editMode, pageId );

        var currentLocation = basePath + pageId +"/" + entityName + "/1/" + targetId;
        history.push(currentLocation);
    }

    render() {

        if (this.props.crudList == null) {return (<tbody><tr><td></td></tr></tbody>)}

        return (
            <tbody>
            {this.props.crudList.list.map((item, index) => {
                return (<tr key={index}>
                    {Object.entries(item).map((item, index) => {



                        if (index != 0) {
                            return  (
                                <td key={index}>{this.getItemRenderByType( this.props.crudList.headers[item[0]].type, item[1]  )}</td>
                                    )
                        } else {
                            return  (
                                <td key={index}>
                                    <a onClick={this.handleClick.bind(this, item[1])}>
                                        {this.getItemRenderByType( this.props.crudList.headers[item[0]].type, item[1]  )}
                                    </a>
                                </td>
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
    return bindActionCreators({ fetchCrudEdit, contextUpdate, resetCrudEdit }, dispatch);
}

function mapStateToProps({ context, crudList }) {
    return { context, crudList };
}

export default connect(mapStateToProps, mapDispatchToProps)(CrudListBody);