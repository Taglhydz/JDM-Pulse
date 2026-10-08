import React, { useCallback, useEffect, useState } from "react";
import CarDetailModal from "../components/CarDetailModal";
import CarImage       from "../components/CarImage";
import Header         from "../components/Header";
import Footer         from "../components/Footer";
import Api            from "../functions/Api";
import "../styles/Profile.css";
import "../styles/Discover.css";

function Profile() {
  const [user       , setUser       ] = useState(null);
  const [cars       , setCars       ] = useState([]);
  const [selectedCar, setSelectedCar] = useState(null);
  const [isDeleting , setIsDeleting ] = useState(false);
  const access_token = sessionStorage.getItem("bearer");
  const userData     = sessionStorage.getItem("user"  );
  const userId = userData ? JSON.parse(userData).id : null;

  // useCallback pour garder la même fonction entre les rendus (sinon le useEffect boucle)
  const fetchUserCars = useCallback(() => {
    Api("GET", `cars/user/${userId}`, null, "", access_token)
      .then(carResponse => {
        if (carResponse && carResponse.status === 200 && carResponse.body && carResponse.body.data) {
          setCars(carResponse.body.data);
        }
      })
      .catch(error => console.error(error));
  }, [userId, access_token]);

  useEffect(() => {
    Api("GET", "users/" + userId, null, "", access_token)
      .then(response => {
        if (response && response.status === 200 && response.body && response.body.data) {
          setUser(response.body.data);
          fetchUserCars();
        }
      })
      .catch(error => console.error(error));
  }, [userId, access_token, fetchUserCars]);

  if (!user) return <div>Chargement...</div>;

  const handleCardClick = (car) => {
    setSelectedCar(car);
  };

  const handleCloseModal = () => {
    setSelectedCar(null);
  };

  const handleDeleteCar = (e, carId) => {
    e.stopPropagation();
    
    if (window.confirm("Êtes-vous sûr de vouloir supprimer cette voiture de votre collection ?")) {
      setIsDeleting(true);
      
      Api("DELETE", `owns/delete/${userId}/${carId}`, null, "", access_token)
        .then(response => {
          if (response && response.body && response.body.success) {
            alert("Voiture supprimée de votre collection !");
            fetchUserCars();
          } else {
            alert("Erreur lors de la suppression de la voiture.");
          }
          setIsDeleting(false);
        })
        .catch(error => {
          console.error("Erreur lors de la suppression :", error);
          alert("Erreur lors de la suppression de la voiture.");
          setIsDeleting(false);
        });
    }
  };

  return (
    <div>
      <Header />
      <div className="profile-page">
        <h1>Profil</h1>
        <div className="profile-info">
          <p><strong>Nom :</strong> {user.last_name}</p>
          <p><strong>Prénom :</strong> {user.first_name}</p>
          <p><strong>Email :</strong> {user.email}</p>
          <p><strong>Rôle :</strong> {user.role === 'admin' ? 'Administrateur' : user.role === 'super_admin' ? 'Super Administrateur' : user.role}</p>
          <p><strong>Date de naissance :</strong> {user.date_of_birth}</p>
        </div>
        <div className="profile-cars">
          <h2>Voitures possédées</h2>
          {cars.length === 0 ? (
            <p>Aucune voiture enregistrée.</p>
          ) : (
            <div className="cars-list">
              {cars.map(car => (
                <div className="car-card" key={car.id} onClick={() => handleCardClick(car)}>
                  <button 
                    className="delete-car-btn" 
                    onClick={(e) => handleDeleteCar(e, car.id)}
                    disabled={isDeleting}
                  >
                    ×
                  </button>
                  <CarImage src={car.image_url} alt={`${car.brand} ${car.model}`} style={{width:'100%',maxWidth:'300px',borderRadius:'8px'}} />
                  <h2>{car.brand} {car.model}</h2>
                  <p>Année : {car.year}</p>
                  <p>Génération : {car.generation}</p>
                  <p>Couleur : {car.color}</p>
                </div>
              ))}
            </div>
          )}
        </div>
        {selectedCar && <CarDetailModal car={selectedCar} onClose={handleCloseModal} />}
      </div>
      <Footer />
    </div>
  );
}

export default Profile;
