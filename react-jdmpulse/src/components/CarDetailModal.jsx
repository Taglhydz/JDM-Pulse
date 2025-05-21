import React, { useEffect, useState } from "react";
import Api from "../functions/Api";
import "../styles/CarDetailModal.css";

function CarDetailModal({ car, onClose }) {
  const [details, setDetails] = useState(null);
  const [loading, setLoading] = useState(false);
  const access_token = sessionStorage.getItem("bearer");

  useEffect(() => {
    if (car) {
      setLoading(true);
      Api("GET", `cars/detail/${car.id}`, null, "", access_token, { 'API-Key': 'Miam0Tacos!' })
        .then(response => {
          console.log('Réponse API car details:', response);
          if (response && response.status === 200 && response.body && response.body.data) {
            setDetails(response.body.data);
            console.log('Détails voiture (data):', response.body.data);
          }
          setLoading(false);
        })
        .catch((err) => {
          console.log('Erreur API car details:', err);
          setLoading(false);
        });
    } else {
      setDetails(null);
    }
  }, [car]);

  useEffect(() => {
    console.log('Valeur de details dans le modal:', details);
  }, [details]);

  if (!car) return null;

  return (
    <div className="car-modal-overlay" onClick={onClose}>
      <div className="car-modal" onClick={e => e.stopPropagation()}>
        <button className="car-modal-close" onClick={onClose}>×</button>
        {loading || !details ? (
          <div style={{marginTop: 80}}>Chargement...</div>
        ) : (
          <React.Fragment>
            <img src={details.car.image_url || car.image_url} alt={`${details.car.brand} ${details.car.model}`} />
            <hr className="car-modal-separator" />
            <h2>{details.car.brand} {details.car.model}</h2>
            <p><strong>Année :</strong> {details.car.year}</p>
            <p><strong>Génération :</strong> {details.car.generation}</p>
            <p><strong>Couleur :</strong> {details.car.color}</p>
            {details.edition && (
              <p><strong>Edition :</strong> {details.edition.edition_name}</p>
            )}
            {details.engines && details.engines.length > 0 && (
              <div className="car-engine-section">
                <h3 className="car-engine-title">Moteurs</h3>
                <table className="car-engine-table">
                  <thead>
                    <tr>
                      <th className="car-engine-th">Nom moteur / Volume</th>
                      <th className="car-engine-th">Admission</th>
                      <th className="car-engine-th">Architecture</th>
                      <th className="car-engine-th">Carburant</th>
                      <th className="car-engine-th">Conso</th>
                      <th className="car-engine-th">Puissance</th>
                      <th className="car-engine-th">Couple</th>
                    </tr>
                  </thead>
                  <tbody>
                    {details.engines.map((engine, idx) => {
                      const motorizations = engine.motorizations || [];
                      if (motorizations.length === 0) {
                        return (
                          <tr key={engine.id || idx}>
                            <td className="car-engine-td car-engine-td-title">{engine.engine_name} <span style={{color:'#888'}}>({engine.volume})</span></td>
                            <td className="car-engine-td">{engine.induction}</td>
                            <td className="car-engine-td">{engine.architecture}</td>
                            <td className="car-engine-td">{engine.fuel_type}</td>
                            <td className="car-engine-td"></td>
                            <td className="car-engine-td car-engine-td-empty" colSpan={2}>Aucune motorisation</td>
                          </tr>
                        );
                      }
                      return motorizations.map((moto, mIdx) => (
                        <tr key={engine.id + '-' + mIdx}>
                          {mIdx === 0 && (
                            <>
                              <td className="car-engine-td car-engine-td-title" rowSpan={motorizations.length}>{engine.engine_name} <span style={{color:'#888'}}>({engine.volume})</span></td>
                              <td className="car-engine-td" rowSpan={motorizations.length}>{engine.induction}</td>
                              <td className="car-engine-td" rowSpan={motorizations.length}>{engine.architecture}</td>
                              <td className="car-engine-td" rowSpan={motorizations.length}>{engine.fuel_type}</td>
                            </>
                          )}
                          <td className="car-engine-td">{moto.consumption || ''}</td>
                          <td className="car-engine-td">{moto.power}</td>
                          <td className="car-engine-td">{moto.torque}</td>
                        </tr>
                      ));
                    })}
                  </tbody>
                </table>
              </div>
            )}
          </React.Fragment>
        )}
      </div>
    </div>
  );
}

export default CarDetailModal;

