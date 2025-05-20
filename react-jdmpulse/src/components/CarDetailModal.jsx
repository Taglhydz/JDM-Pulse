import React from "react";
import "../styles/CarDetailModal.css";

function CarDetailModal({ car, onClose }) {
  if (!car) return null;
  return (
    <div className="car-modal-overlay" onClick={onClose}>
      <div className="car-modal" onClick={e => e.stopPropagation()}>
        <button className="car-modal-close" onClick={onClose}>×</button>
        <img src={car.image_url} alt={`${car.brand} ${car.model}`} style={{width:'100%',maxWidth:'350px',borderRadius:'8px'}} />
        <h2>{car.brand} {car.model}</h2>
        <p><strong>Année :</strong> {car.year}</p>
        <p><strong>Génération :</strong> {car.generation}</p>
        <p><strong>Couleur :</strong> {car.color}</p>
		<p><strong>Edition :</strong> {car.edition} €</p>
      </div>
    </div>
  );
}

export default CarDetailModal;

