import React, { useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import "../styles/Dashboard.css";
import { useNavigate } from "react-router-dom";
import Api from "../functions/Api";
import ViewAllModal from "../components/ViewAllModal";

function Dashboard() {
  const userData = sessionStorage.getItem("user");
  const user = userData ? JSON.parse(userData) : null;
  const isSuperAdmin = user && user.role === "superAdmin";

  const [carMode, setCarMode] = useState("create");
  const [engineMode, setEngineMode] = useState("create");
  const [editionMode, setEditionMode] = useState("create");
  const [motoMode, setMotoMode] = useState("create");
  const [userMode, setUserMode] = useState("create");
  const [modalOpen, setModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState("");
  const [modalData, setModalData] = useState([]);
  const [modalColumns, setModalColumns] = useState([]);

  const handleViewAll = async (entity) => {
    let route = "";
    let columns = [];
    let title = "";
    if (entity === "cars") {
      route = "cars/all";
      columns = ["id", "brand", "model", "year", "color", "generation", "image_url", "edition_id"];
      title = "Toutes les voitures";
    } else if (entity === "editions") {
      route = "editions/all";
      columns = ["id", "edition_name"];
      title = "Toutes les éditions";
    } else if (entity === "engines") {
      route = "engines/all";
      columns = ["id", "engine_name", "architecture", "volume", "induction", "fuel_type"];
      title = "Tous les moteurs";
    } else if (entity === "motorizations") {
      route = "motorizations/all";
      columns = ["id", "power", "torque", "consumption", "engine_id"];
      title = "Toutes les motorisations";
    } else if (entity === "users") {
      route = "users/all";
      columns = ["id", "name", "email", "role"];
      title = "Tous les utilisateurs";
    }
    try {
      const token = sessionStorage.getItem("bearer");
      const response = await Api("GET", route, null, "", token, { 'API-Key': 'Miam0Tacos!' });
      let data = response && response.body && response.body.data ? response.body.data : [];
      
      if (entity === "cars") {
        data = data.map(car => {
          const formattedCar = { ...car };
          formattedCar.edition_id = car.edition ? car.edition.edition_id : 'Null';
          return formattedCar;
        });
      } else if (entity === "motorizations") {
        data = data.map(moto => {
          const formattedMoto = { ...moto };
          if (moto.engine && moto.engine.id) {
            formattedMoto.engine_id = moto.engine.id;
          }
          return formattedMoto;
        });
      }
      
      setModalTitle(title);
      setModalColumns(columns);
      setModalData(data);
      setModalOpen(true);
    } catch (e) {
      console.error("Erreur lors du chargement des données:", e);
      setModalTitle(title);
      setModalColumns(columns);
      setModalData([]);
      setModalOpen(true);
    }
  };

  return (
    <>
      <Header />
      <div className="dashboard-container">
        <div className={isSuperAdmin ? "dashboard-grid dashboard-grid-3" : "dashboard-grid dashboard-grid-2"}>
          <div>
            {/* Cars */}
            <section className="dashboard-section">
              <h2>Voiture</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('cars')}>Voir tout</button>
                <button onClick={() => setCarMode('create')}>Créer</button>
                <button onClick={() => setCarMode('update')}>Update</button>
              </div>
              {carMode !== 'view' && (
                <form className="dashboard-form">
                  <input type="text" placeholder="Marque" name="brand" />
                  <input type="text" placeholder="Modèle" name="model" />
                  <input type="text" placeholder="Année" name="year" />
                  <input type="text" placeholder="Couleur" name="color" />
                  <input type="text" placeholder="Génération" name="generation" />
                  <input type="text" placeholder="Image URL" name="image_url" />
                  <input type="text" placeholder="Edition ID" name="edition_id" />
                  <button type="submit">Valider</button>
                </form>
              )}
            </section>
            {/* Editions */}
            <section className="dashboard-section">
              <h2>Édition</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('editions')}>Voir tout</button>
                <button onClick={() => setEditionMode('create')}>Créer</button>
                <button onClick={() => setEditionMode('update')}>Update</button>
              </div>
              {editionMode !== 'view' && (
                <form className="dashboard-form">
                  <input type="text" placeholder="Nom de l'édition" name="edition_name" />
                  <button type="submit">Valider</button>
                </form>
              )}
            </section>
          </div>
          <div>
            {/* Engines */}
            <section className="dashboard-section">
              <h2>Moteur</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('engines')}>Voir tout</button>
                <button onClick={() => setEngineMode('create')}>Créer</button>
                <button onClick={() => setEngineMode('update')}>Update</button>
              </div>
              {engineMode !== 'view' && (
                <form className="dashboard-form">
                  <input type="text" placeholder="Nom moteur" name="engine_name" />
                  <input type="text" placeholder="Architecture" name="architecture" />
                  <input type="text" placeholder="Volume" name="volume" />
                  <input type="text" placeholder="Induction" name="induction" />
                  <input type="text" placeholder="Carburant" name="fuel_type" />
                  <button type="submit">Valider</button>
                </form>
              )}
            </section>
            {/* Motorizations */}
            <section className="dashboard-section">
              <h2>Motorisation</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('motorizations')}>Voir tout</button>
                <button onClick={() => setMotoMode('create')}>Créer</button>
                <button onClick={() => setMotoMode('update')}>Update</button>
              </div>
              {motoMode !== 'view' && (
                <form className="dashboard-form">
                  <input type="text" placeholder="Puissance" name="power" />
                  <input type="text" placeholder="Couple" name="torque" />
                  <input type="text" placeholder="Engine ID" name="engine_id" />
                  <button type="submit">Valider</button>
                </form>
              )}
            </section>
          </div>
          {isSuperAdmin && (
            <div>
              {/* Users */}
              <section className="dashboard-section">
                <h2>Utilisateur</h2>
                <div className="dashboard-actions">
                  <button onClick={() => handleViewAll('users')}>Voir tout</button>
                  <button onClick={() => setUserMode('create')}>Créer</button>
                  <button onClick={() => setUserMode('update')}>Update</button>
                </div>
                {userMode !== 'view' && (
                  <form className="dashboard-form">
                    <input type="text" placeholder="Nom" name="name" />
                    <input type="email" placeholder="Email" name="email" />
                    <input type="password" placeholder="Mot de passe" name="password" />
                    <select name="role">
                      <option value="user">User</option>
                      <option value="admin">Admin</option>
                      <option value="superAdmin">SuperAdmin</option>
                    </select>
                    <button type="submit">Valider</button>
                  </form>
                )}
              </section>
            </div>
          )}
        </div>
        <ViewAllModal open={modalOpen} onClose={() => setModalOpen(false)} title={modalTitle} data={modalData} columns={modalColumns} />
      </div>
      <Footer />
    </>
  );
}

export default Dashboard;
