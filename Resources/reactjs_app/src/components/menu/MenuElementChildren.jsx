import React from 'react';

import MenuElement from './MenuElement.jsx';

export default class MenuElementChildren extends React.Component {

    render() {

        if (typeof this.props.element.children == 'undefined') { return null}

        return (
            <ul className="sub-menu">
                {this.props.element.children.map((child, index) => { return <MenuElement key={index} element={child} />})}
            </ul>
        );
    }

}
