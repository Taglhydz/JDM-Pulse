import React, { useEffect, useState } from "react";
import Api from "../functions/Api";
import Header from "../components/Header";
import Footer from "../components/Footer";
import "../styles/Profile.css";

function Profile() {
  const [user, setUser] = useState(null);
  const [cars, setCars] = useState([]);
  const access_token = sessionStorage.getItem("bearer");
  const userData = sessionStorage.getItem("user");
  const userId = userData ? JSON.parse(userData).id : null;

  useEffect(() => {
    Api("GET", "users/" + userId, null, "", access_token, { "API-Key": "Miam0Tacos!" })
      .then(response => {
        if (response && response.status === 200 && response.body && response.body.data) {
          setUser(response.body.data);
          Api("GET", `cars/user/${response.body.data.id}`, null, "", access_token, { "API-Key": "Miam0Tacos!" })
            .then(carResponse => {
              if (carResponse && carResponse.status === 200 && carResponse.body && carResponse.body.data) {
                setCars(carResponse.body.data);
              }
            });
        }
      })
      .catch(error => console.error(error));
  }, []);

  if (!user) return <div>Chargement...</div>;

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
            <ul>
              {cars.map(car => (
                <li key={car.id}>{car.brand} {car.model} ({car.year})</li>
              ))}
            </ul>
          )}
        </div>
      </div>
      <Footer />
    </div>
  );
}

export default Profile;
