import React from 'react';

import StringView from '../data_types/string/StringView.jsx';

export default class CrudListBody extends React.Component {

    getItemRenderByType ( type, data ) {
        switch (type) {
            case "string":
                return <StringView data={data} />;
        }

    }

    render() {
        return (
            <tbody>
            {this.props.list.map((item, index) => {
                return (<tr key={index}>
                    {Object.entries(item).map((item, index) => {
                        return  <td key={index}>{this.getItemRenderByType( this.props.headers[item[0]].type, item[1]  )}</td>
                    })}
                </tr>)
            })}
            </tbody>
        );
    }

}