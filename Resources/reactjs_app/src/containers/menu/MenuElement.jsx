import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Link, Redirect } from 'react-router-dom';
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import MenuElementChildren from '../../components/menu/MenuElementChildren.jsx';
import { fetchPage } from '../../actions/action_page.jsx';
import { resetCrudList } from '../../actions/action_crud_list.jsx';
import { fetchList } from '../../actions/action_crud_list.jsx';
import { contextUpdate } from '../../actions/action_context.jsx';

class MenuElement extends React.Component {

    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }

    /**
     * Valeur de l'attribut href
     *
     * @param element
     * @returns {*}
     */
    getHref(element){
        if (element.type == 'external') {
            return element.parameters.url;
        }

        return "javascript:;"
    }

    handleClick(e) {

        //entityName, targetId, mode, pageId
        var entityName  = this.props.element.parameters.entity;
        var targetId    = "0";
        var pageId      = this.props.element.parameters.id;
        var editMode    = "0";

        this.props.contextUpdate ( entityName, targetId, editMode, pageId );
        this.props.resetCrudList();
        this.props.fetchPage( pageId );

        // Fteching dataList
        this.props.fetchList(entityName);

        var currentLocation = basePath + pageId +"/" + entityName +"/0/" + targetId;
        history.push(currentLocation);

    }

    /**
     * Retourne la structure de l'élément selon son type
     *
     * @param element
     * @returns {XML}
     */
    getMenuElementByType(element){
        if (element.type == "section") {
            return (
                <li className="heading">
                    <h3>{this.props.element.label}</h3>
                </li>
            )
        } else if (element.type == "subMenu") {
            return (
                <li className="nav-item">
                    <a href="javascript:;" className="nav-link nav-toggle">
                        <i className="icon-puzzle"></i>
                        <span className="title">{this.props.element.label}</span>
                        <span className="arrow"></span>
                    </a>

                    <MenuElementChildren element={this.props.element}/>
                </li>
            )
        }
        else {
            return (
                <li className="nav-item">
                    <a className="nav-link nav-toggle" onClick={this.handleClick}>
                        <i className="icon-home"></i>
                        <span className="title">{this.props.element.label}</span>
                        <span className="arrow"></span>
                    </a>
                    <MenuElementChildren element={this.props.element}/>
                </li>
            )
        }
    }

    render(){
        return (
            this.getMenuElementByType(this.props.element)
        )
    };

}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchPage, fetchList, resetCrudList, contextUpdate }, dispatch);
}

function mapStateToProps({ context }) {
    return { context };
}

export default connect(null, mapDispatchToProps)(MenuElement);
