import React from 'react';

export default class MenuSunshinePage extends React.Component {

    render() {

        return (
            <li className="nav-item start active open">
                <a href="javascript:;" className="nav-link nav-toggle">
                    <i className="icon-home"></i>
                    <span className="title">{this.props.element.label}</span>
                    <span className="selected"></span>
                    <span className="arrow open"></span>
                </a>
            </li>
        );
    }

}