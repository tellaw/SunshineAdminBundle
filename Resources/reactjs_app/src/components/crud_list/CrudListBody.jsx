import React from 'react';
import { Link } from 'react-router-dom'
import QueryString from 'query-string';

import StringView from '../data_types/string/StringView.jsx';

export default class CrudListBody extends React.Component {

    getItemRenderByType ( type, data ) {
        switch (type) {
            case "string":
                return <StringView data={data} />;
            case "integer":
                return <StringView data={data} />;
        }

    }

    render() {

        var queryString = QueryString.parse(location.search) ;

        return (
            <tbody>
            {this.props.list.map((item, index) => {
                return (<tr key={index}>
                    {Object.entries(item).map((item, index) => {
                        if (index != 0) {
                            return  (
                                        <td key={index}>{this.getItemRenderByType( this.props.headers[item[0]].type, item[1]  )}</td>
                                    )
                        } else {

                            let currentLocation = location.pathname + "?entity="+queryString.entity+"&editMode=1&targetId="+item[1];

                            return  (
                                <td key={index}><Link to={currentLocation}>{this.getItemRenderByType( this.props.headers[item[0]].type, item[1]  )}</Link></td>
                            )
                        }
                    })}
                </tr>)
            })}
            </tbody>
        );
    }

}