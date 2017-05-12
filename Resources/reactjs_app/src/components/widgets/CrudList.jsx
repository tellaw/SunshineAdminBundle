import React from 'react';
import QueryString from 'query-string';

import { CrudApiList } from '../../api/CrudApi.jsx';

export default class CrudList extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            entity : "",
            queryString : {},
            data : []
        };
    }

    componentWillMount() {

        // Get Query String parameter for entity
        this.setState ( QueryString.parse(location.search) );
/*
        if ( this.state.queryString.entity != undefined ) {
            console.log (this.state.queryString);
            CrudApiList ( this.state.queryString.entity ).
            then(function(data){
                this.setState({page:data});
            }.bind(this));
        }
*/
    }

    render()
    {

        //console.log (QueryString.parse(location.search));

        return (
            <div className="portlet box red">
                <div className="portlet-title">
                    <div className="caption">
                        <i className="fa fa-picture"></i>{this.props.widget.title}</div>
                    <div className="tools">
                        <a href="javascript:;" className="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" className="config" data-original-title="" title=""> </a>
                        <a href="javascript:;" className="reload" data-original-title="" title=""> </a>
                        <a href="javascript:;" className="remove" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div className="portlet-body">
                    <div className="table-scrollable">
                        <table className="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th> First Name </th>
                                <th> Last Name </th>
                                <th> Username </th>
                                <th> Status </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> 1 </td>
                                <td> Mark </td>
                                <td> Otto </td>
                                <td> makr124 </td>
                                <td>
                                    <span className="label label-sm label-success"> Approved </span>
                                </td>
                            </tr>
                            <tr>
                                <td> 2 </td>
                                <td> Jacob </td>
                                <td> Nilson </td>
                                <td> jac123 </td>
                                <td>
                                    <span className="label label-sm label-info"> Pending </span>
                                </td>
                            </tr>
                            <tr>
                                <td> 3 </td>
                                <td> Larry </td>
                                <td> Cooper </td>
                                <td> lar </td>
                                <td>
                                    <span className="label label-sm label-warning"> Suspended </span>
                                </td>
                            </tr>
                            <tr>
                                <td> 4 </td>
                                <td> Sandy </td>
                                <td> Lim </td>
                                <td> sanlim </td>
                                <td>
                                    <span className="label label-sm label-danger"> Blocked </span>
                                </td>
                            </tr>
                            <tr>
                                <td> 5 </td>
                                <td> Sandy </td>
                                <td> Lim </td>
                                <td> sanlim </td>
                                <td>
                                    <span className="label label-sm label-danger"> Blocked </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        );
    }

}