import React from 'react';


export default class CrudListHead extends React.Component {

    render() {
        return (
            <thead>
            <tr>
                {Object.entries(this.props.headers).map((item, index) => {
                    return (<th key={index}>{item[1].label}</th>)
                })}
            </tr>
            </thead>
        );
    }

}