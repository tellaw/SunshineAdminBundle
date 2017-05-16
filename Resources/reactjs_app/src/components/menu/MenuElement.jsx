import React from 'react';

import MenuElementChildren from './MenuElementChildren.jsx';

export default class MenuElement extends React.Component {

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
                    <MenuElementChildren element={this.props.element} />
                </li>
            )
        } else {
            return (
                <li className="nav-item">
                    <a href={this.getHref(element)} className="nav-link nav-toggle">
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
