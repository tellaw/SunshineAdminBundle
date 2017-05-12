import React from 'react';

export default class DemoWidget extends React.Component {

    render() {

        return (
            <div className="dashboard-stat2 ">
                <div className="display">
                    <div className="number">
                        <h3 className="font-green-sharp">
                            <span data-counter="counterup" data-value="7800">7800</span>
                            <small className="font-green-sharp">$</small>
                        </h3>
                        <small>DEMO WIDGET</small>
                    </div>
                    <div className="icon">
                        <i className="icon-pie-chart"></i>
                    </div>
                </div>
                <div className="progress-info">
                    <div className="progress">
                                            <span style={{'width': '76%'}} className="progress-bar progress-bar-success green-sharp">
                                                <span className="sr-only">76% progress</span>
                                            </span>
                    </div>
                    <div className="status">
                        <div className="status-title"> progress </div>
                        <div className="status-number"> 76% </div>
                    </div>
                </div>
            </div>
        );
    }

}