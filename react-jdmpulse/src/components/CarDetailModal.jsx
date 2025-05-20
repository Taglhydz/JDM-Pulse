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
            <h2>{details.car.brand} {details.car.model}</h2>
            <p><strong>Année :</strong> {details.car.year}</p>
            <p><strong>Génération :</strong> {details.car.generation}</p>
            <p><strong>Couleur :</strong> {details.car.color}</p>
            {details.edition && (
              <p><strong>Edition :</strong> {details.edition.edition_name}</p>
            )}
            {details.engines && details.engines.length > 0 && (
              <div style={{width: '100%', marginTop: 24}}>
                <h3 style={{marginBottom: 8}}>Moteurs</h3>
                <table style={{width: '100%', borderCollapse: 'collapse', background: '#f8f8f8', borderRadius: 8}}>
                  <thead>
                    <tr style={{background: '#e0e0e0'}}>
                      <th style={{padding: '8px', border: '1px solid #ccc'}}>Nom moteur / Volume</th>
                      <th style={{padding: '8px', border: '1px solid #ccc'}}>Induction</th>
                      <th style={{padding: '8px', border: '1px solid #ccc'}}>Architecture</th>
                      <th style={{padding: '8px', border: '1px solid #ccc'}}>Carburant</th>
                      <th style={{padding: '8px', border: '1px solid #ccc'}}>Motorisations<br/>(Puissance / Couple)</th>
                    </tr>
                  </thead>
                  <tbody>
                    {details.engines.map((engine, idx) => (
                      <tr key={engine.id || idx}>
                        <td style={{padding: '8px', border: '1px solid #ccc', fontWeight: 'bold'}}>{engine.engine_name} <span style={{color:'#888'}}>({engine.volume})</span></td>
                        <td style={{padding: '8px', border: '1px solid #ccc'}}>{engine.induction}</td>
                        <td style={{padding: '8px', border: '1px solid #ccc'}}>{engine.architecture}</td>
                        <td style={{padding: '8px', border: '1px solid #ccc'}}>{engine.fuel_type}</td>
                        <td style={{padding: '8px', border: '1px solid #ccc'}}>
                          {engine.motorizations && engine.motorizations.length > 0 ? (
                            <table style={{width:'100%', borderCollapse:'collapse', background:'none'}}>
                              <tbody>
                                {engine.motorizations.map((moto, mIdx) => (
                                  <tr key={moto.id || mIdx}>
                                    <td style={{padding: '2px 8px', border: 'none'}}>Puissance : <b>{moto.power}</b></td>
                                    <td style={{padding: '2px 8px', border: 'none'}}>Couple : <b>{moto.torque}</b></td>
                                  </tr>
                                ))}
                              </tbody>
                            </table>
                          ) : (
                            <span style={{color:'#888'}}>Aucune motorisation</span>
                          )}
                        </td>
                      </tr>
                    ))}
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

