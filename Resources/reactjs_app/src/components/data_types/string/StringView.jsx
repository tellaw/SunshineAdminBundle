import React from 'react';

export default class StringView extends React.Component {

    render() {
        return (<span>{this.props.data}</span>);
    }

}