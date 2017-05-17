import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { fetchMenu } from '../actions/action_menu.jsx';

import MenuElement from '../components/menu/MenuElement.jsx';

class Sidebar extends React.Component {

    componentWillMount() {
        this.props.fetchMenu();
    }

    componentDidMount() {
        Layout.init();
        console.log (Layout);
        console.log ("My Fucking layout has been loaded!!!!");
    }
    render() {

        if (this.props.menu == null) {console.log ('pas de menu');return (<div></div>)}

        return (
            <div className="page-sidebar-wrapper">
                <div className="page-sidebar navbar-collapse collapse">
                    <ul className="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style={{paddingTop: '20px'}}>
                        {this.props.menu.map((menuElement, index) => {
                            return (<MenuElement element={menuElement} key={index} />)
                        })}
                    </ul>
                </div>
            </div>
        );
    }
}

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ fetchMenu }, dispatch);
}

function mapStateToProps({ menu }) {
    return { menu };
}

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar);
