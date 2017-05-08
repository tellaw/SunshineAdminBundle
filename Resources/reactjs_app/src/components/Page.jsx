import React from 'react';
import PageApi from '../api/PageApi.jsx';
import Row from './page/Row.jsx';

export default class Page extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            page : {
                title: "",
                description: "",
                rows : [

                ]
            }
        };
    }

    componentWillMount() {
        api.get( this.props.match.params.pageId ).
        then(function(data){
            this.setState({page:data});
        }.bind(this));
    }

    render() {
        return (
            <div className="page-content-wrapper">
                <div className="page-content">

                    <h1 className="page-title" id="reactContent"> {this.state.page.title}
                        <small>{this.state.page.description}</small>
                    </h1>

                    {this.state.page.rows.map((row, index) => {
                        return <Row key={index} row={row} />
                    })}

                </div>
            </div>
        );
    }
}