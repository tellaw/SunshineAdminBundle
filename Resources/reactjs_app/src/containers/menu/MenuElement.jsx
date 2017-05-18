import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Link, Redirect } from 'react-router-dom';
import QueryString from 'query-string';
import createBrowserHistory  from 'history/createBrowserHistory'
const history = createBrowserHistory();

import MenuElementChildren from '../../components/menu/MenuElementChildren.jsx';
import { fetchPage } from '../../actions/action_page.jsx';
import { fetchList } from '../../actions/action_crud_list.jsx';

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
        e.preventDefault();
        var element = this.props.element;
        console.log('sidebar fetch page');
        console.log(element.parameters.id);
        this.props.fetchPage( element.parameters.id );
        console.log('sidebar fetch list');
        // Get Query String parameter for entity
        var queryString = QueryString.parse(location.search) ;
        // Fteching dataList
        this.props.fetchList(queryString.entity);
        console.log('query string : ' + queryString.entity);
        history.push(basePath + element.parameters.id + '?entity=' + element.parameters.entity);
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
                    <a href="javascript:" className="nav-link nav-toggle">
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
    return bindActionCreators({ fetchPage, fetchList }, dispatch);
}

export default connect(null, mapDispatchToProps)(MenuElement);
