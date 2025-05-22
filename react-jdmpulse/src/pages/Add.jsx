import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import Api    from "../functions/Api";
import CarDetailModal from "../components/CarDetailModal";
import "../styles/Add.css";

function Add() {
  const [cars,         setCars        ] = useState([]);
  const [search,       setSearch      ] = useState("");
  const [filteredCars, setFilteredCars] = useState([]);
  const [selectedCar,  setSelectedCar ] = useState(null);
  const [isAdding,     setIsAdding    ] = useState(false);
  const [ownedCarIds,  setOwnedCarIds ] = useState([]);
  const access_token = sessionStorage.getItem("bearer");
  const userData     = sessionStorage.getItem("user"  );
  const userId = userData ? JSON.parse(userData).id : null;

  useEffect(() => {
    Api("GET", "cars/all", null, "", access_token, { 'API-Key': 'Miam0Tacos!' })
      .then(response => {
        if (response && response.status === 200 && response.body && response.body.data) {
          setCars(response.body.data);
          setFilteredCars(response.body.data);
        }
      })
      .catch(error => console.error(error));
  }, []);

  useEffect(() => {
    if (!search) {
      setFilteredCars(cars);
    } else {
      const lower = search.toLowerCase();
      setFilteredCars(
        cars.filter(car =>
          car.brand.toLowerCase().includes(lower) ||
          car.model.toLowerCase().includes(lower)
        )
      );
    }
  }, [search, cars]);

  useEffect(() => {
    if (userId) {
      Api("GET", `cars/user/${userId}`, null, "", access_token, { 'API-Key': 'Miam0Tacos!' })
        .then(response => {
          if (response && response.body && response.body.data) {
            const ids = response.body.data.map(own => own.car_id || own.id);
            setOwnedCarIds(ids.map(id => parseInt(id)));
          }
        })
        .catch(error => console.error(error));
    }
  }, [userId]);

  const handleAddCar = (e, carId) => {
    e.stopPropagation();
    if (isAdding) return;
    setIsAdding(true);
    Api("POST", "owns/create", { user_id: userId, car_id: carId }, "", access_token, { 'API-Key': 'Miam0Tacos!' })
      .then(response => {
        if (response && response.body && response.body.success) {
          alert("Voiture ajoutée à votre collection !");
        } else {
          alert("Erreur lors de l'ajout de la voiture.");
        }
        setIsAdding(false);
      })
      .catch(error => {
        alert("Erreur lors de l'ajout de la voiture.");
        setIsAdding(false);
      });
  };

  const handleCardClick = (car) => {
    setSelectedCar(car);
  };

  const handleCloseModal = () => {
    setSelectedCar(null);
  };

  return (
    <>
      <Header />
      <div className="add-page">
        <div className="add-header">
          <h1>Ajouter une voiture à ma collection</h1>
          <input
            className="add-search-bar"
            type="text"
            placeholder="Rechercher par marque ou modèle..."
            value={search}
            onChange={e => setSearch(e.target.value)}
          />
        </div>
        <div className="cars-list">
          {filteredCars.length === 0 ? (
            <p>Aucune voiture trouvée.</p>
          ) : (
            filteredCars.map(car => (
              <div className="car-card" key={car.id} onClick={() => handleCardClick(car)}>
                <button
                  className="add-car-btn"
                  onClick={e => handleAddCar(e, car.id)}
                  disabled={isAdding || ownedCarIds.includes(car.id)}
                  title={ownedCarIds.includes(car.id) ? "Déjà dans votre collection" : "Ajouter à ma collection"}
                >
                  {ownedCarIds.includes(car.id) ? '✓' : '+'}
                </button>
                <img src={car.image_url} alt={`${car.brand} ${car.model}`} style={{width:'100%',maxWidth:'300px',borderRadius:'8px'}} />
                <h2>{car.brand} {car.model}</h2>
                <p>Année : {car.year}</p>
                <p>Génération : {car.generation}</p>
                <p>Couleur : {car.color}</p>
              </div>
            ))
          )}
        </div>
        {selectedCar && <CarDetailModal car={selectedCar} onClose={handleCloseModal} />}
      </div>
      <Footer />
    </>
  );
}

export default Add;
