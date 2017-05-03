import React from 'react';

export default class Page extends React.Component {
    render() {
        return (
            <div className="page-content-wrapper">
                <div className="page-content">
                    <h1 className="page-title" id="reactContent"> Admin Dashboard
                        <small>statistics, charts, recent events and reports</small>
                    </h1>
                </div>
            </div>
        );
    }
}