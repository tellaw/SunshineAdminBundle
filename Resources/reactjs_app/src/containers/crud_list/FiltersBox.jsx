import React from 'react';

import { connect } from 'react-redux';
import { bindActionCreators } from 'redux'

class FiltersBox extends React.Component {

    render() {

        return (
            <div className="portlet light bordered">
                <div className="portlet-title">
                    <div className="caption">
                        <i className="fa fa-gift"></i> Filtres </div>
                </div>
                <div className="portlet-body form">
                    <form role="form">
                        <div>
                            <div className="form-group form-md-line-input form-md-floating-label">
                                <input  type="text"
                                        className="form-control"
                                        id="form_control_1"
                                />
                                    <label htmlFor="form_control_1">Identifiant</label>
                                    <span className="help-block">Filtrez vos données par ID...</span>
                            </div>
                            <div className="form-group form-md-line-input form-md-floating-label">
                                <input  type="text"
                                        className="form-control"
                                        id="form_control_1"
                                />
                                <label htmlFor="form_control_1">Nom de projet</label>
                                <span className="help-block">Filtrez vos données par nom de projet...</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div className="form-actions noborder">
                    <button type="button" className="btn blue">Filtrer</button>
                    <button type="button" className="btn default">Annuler les filtres</button>
                </div>
            </div>
        );
    }

}

function mapStateToProps({ crudList }) {
    return { crudList };
}

export default connect(mapStateToProps, null)(FiltersBox);