import React, { useState, useEffect } from "react";
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
  const [ownMode, setOwnMode] = useState("create");
  const [powerMode, setPowerMode] = useState("create");

  const [modalOpen, setModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState("");
  const [modalData, setModalData] = useState([]);
  const [modalColumns, setModalColumns] = useState([]);
  const [currentEntityType, setCurrentEntityType] = useState("");

  // États pour les formulaires
  const [carForm, setCarForm] = useState({ brand: '', model: '', year: '', color: '', generation: '', image_url: '', edition_id: '' });
  const [engineForm, setEngineForm] = useState({ engine_name: '', architecture: '', volume: '', induction: '', fuel_type: '' });
  const [editionForm, setEditionForm] = useState({ edition_name: '' });
  const [motoForm, setMotoForm] = useState({ power: '', torque: '', consumption: '', engine_id: '' });
  const [userForm, setUserForm] = useState({ first_name: '', last_name: '', date_of_birth: '', email: '', password: '', role: 'user' });
  const [ownForm, setOwnForm] = useState({ car_id: '', user_id: '' });
  const [powerForm, setPowerForm] = useState({ car_id: '', engine_id: '' });

  // États pour stocker les IDs des éléments en cours de modification
  const [currentCarId, setCurrentCarId] = useState(null);
  const [currentEngineId, setCurrentEngineId] = useState(null);
  const [currentEditionId, setCurrentEditionId] = useState(null);
  const [currentMotoId, setCurrentMotoId] = useState(null);
  const [currentUserId, setCurrentUserId] = useState(null);
  const [currentOwnId, setCurrentOwnId] = useState(null);
  const [currentPowerId, setCurrentPowerId] = useState(null);

  // Handlers de changement
  const handleCarChange = e => setCarForm({ ...carForm, [e.target.name]: e.target.value });
  const handleEngineChange = e => setEngineForm({ ...engineForm, [e.target.name]: e.target.value });
  const handleEditionChange = e => setEditionForm({ ...editionForm, [e.target.name]: e.target.value });
  const handleMotoChange = e => setMotoForm({ ...motoForm, [e.target.name]: e.target.value });
  const handleUserChange = e => setUserForm({ ...userForm, [e.target.name]: e.target.value });
  const handleOwnChange = e => setOwnForm({ ...ownForm, [e.target.name]: e.target.value });
  const handlePowerChange = e => setPowerForm({ ...powerForm, [e.target.name]: e.target.value });

  // Reset formulaires
  const resetCarForm = () => {
    setCarForm({ brand: '', model: '', year: '', color: '', generation: '', image_url: '', edition_id: '' });
    setCurrentCarId(null);
    setCarMode("create");
  };

  const resetEngineForm = () => {
    setEngineForm({ engine_name: '', architecture: '', volume: '', induction: '', fuel_type: '' });
    setCurrentEngineId(null);
    setEngineMode("create");
  };

  const resetEditionForm = () => {
    setEditionForm({ edition_name: '' });
    setCurrentEditionId(null);
    setEditionMode("create");
  };

  const resetMotoForm = () => {
    setMotoForm({ power: '', torque: '', consumption: '', engine_id: '' });
    setCurrentMotoId(null);
    setMotoMode("create");
  };

  const resetUserForm = () => {
    setUserForm({ first_name: '', last_name: '', date_of_birth: '', email: '', password: '', role: 'user' });
    setCurrentUserId(null);
    setUserMode("create");
  };

  const resetOwnForm = () => {
    setOwnForm({ car_id: '', user_id: '' });
    setCurrentOwnId(null);
    setOwnMode("create");
  };

  const resetPowerForm = () => {
    setPowerForm({ car_id: '', engine_id: '' });
    setCurrentPowerId(null);
    setPowerMode("create");
  };
  // Sélectionner un élément pour la mise à jour
  const handleSelectForUpdate = (item) => {
    switch (currentEntityType) {
      case "cars":
        setCarForm({
          brand: item.brand || '',
          model: item.model || '',
          year: item.year || '',
          color: item.color || '',
          generation: item.generation || '',
          image_url: item.image_url || '',
          edition_id: item.edition ? item.edition.id : (item.edition_id || '')
        });
        setCurrentCarId(item.id);
        setCarMode("update");
        break;
      case "engines":
        setEngineForm({
          engine_name: item.engine_name || '',
          architecture: item.architecture || '',
          volume: item.volume || '',
          induction: item.induction || '',
          fuel_type: item.fuel_type || ''
        });
        setCurrentEngineId(item.id);
        setEngineMode("update");
        break;
      case "editions":
        setEditionForm({
          edition_name: item.edition_name || ''
        });
        setCurrentEditionId(item.id);
        setEditionMode("update");
        break;
      case "motorizations":
        setMotoForm({
          power: item.power || '',
          torque: item.torque || '',
          consumption: item.consumption || '',
          engine_id: item.engine_id || ''
        });
        setCurrentMotoId(item.id);
        setMotoMode("update");
        break;
      case "users":
        setUserForm({
          first_name: item.first_name || '',
          last_name: item.last_name || '',
          date_of_birth: item.date_of_birth || '',
          email: item.email || '',
          password: '',
          role: item.role || 'user'
        });
        setCurrentUserId(item.id);
        setUserMode("update");
        break;
      case "owns":
        setOwnForm({
          car_id: item.car_id || '',
          user_id: item.user_id || ''
        });
        setCurrentOwnId(item.id);
        setOwnMode("update");
        break;
      case "powers":
        setPowerForm({
          car_id: item.car_id || '',
          engine_id: item.engine_id || ''
        });
        setCurrentPowerId(item.id);
        setPowerMode("update");
        break;
      default:
        break;
    }
  };
  const handleViewAll = async (entity) => {
    let route = "";
    let columns = [];
    let title = "";
    setCurrentEntityType(entity);

    // Vérification que seuls les superAdmin peuvent accéder aux utilisateurs
    if (entity === "users" && !isSuperAdmin) {
      alert("Seuls les super administrateurs peuvent accéder à cette ressource");
      return;
    }

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
    } else if (entity === "owns") {
      route = "owns/all";
      columns = ["id", "car_id", "user_id"];
      title = "Toutes les possessions";
    } else if (entity === "powers") {
      route = "powers/all";
      columns = ["id", "car_id", "engine_id"];
      title = "Toutes les motorisations";
    }
    try {
      const token = sessionStorage.getItem("bearer");
      const response = await Api("GET", route, null, "", token, { 'API-Key': 'Miam0Tacos!' });
      let data = response && response.body && response.body.data ? response.body.data : [];
      
      if (entity === "cars") {
        data = data.map(car => {
          const formattedCar = { ...car };
          if (car.edition) {
            formattedCar.edition_id = car.edition.id;
          } else if (!formattedCar.edition_id) {
            formattedCar.edition_id = '';
          }
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
  // Fonction générique pour gérer les soumissions de formulaires
  const handleEntitySubmit = async (e, entityType, formData, currentId, mode, resetFormFunc) => {
    e.preventDefault();
    
    try {
      // Utilisation de la fonction centralisée dans Api.jsx
      const result = await Api.handleEntitySubmit(entityType, formData, currentId, mode);
      
      if (result.success) {
        alert(result.message);
        resetFormFunc();
      } else {
        alert(result.message);
      }
    } catch (err) {
      alert(`Erreur lors de ${mode === "update" ? "la mise à jour" : "la création"}`);
      console.error("Erreur:", err);
    }
  };

  // Création/Mise à jour voiture
  const handleSubmitCar = (e) => handleEntitySubmit(e, "cars", carForm, currentCarId, carMode, resetCarForm);

  // Création/Mise à jour moteur
  const handleSubmitEngine = (e) => handleEntitySubmit(e, "engines", engineForm, currentEngineId, engineMode, resetEngineForm);

  // Création/Mise à jour édition
  const handleSubmitEdition = (e) => handleEntitySubmit(e, "editions", editionForm, currentEditionId, editionMode, resetEditionForm);

  // Création/Mise à jour motorisation
  const handleSubmitMoto = (e) => handleEntitySubmit(e, "motorizations", motoForm, currentMotoId, motoMode, resetMotoForm);

  // Création/Mise à jour utilisateur
  const handleSubmitUser = (e) => handleEntitySubmit(e, "users", userForm, currentUserId, userMode, resetUserForm);

  // Création/Mise à jour possession
  const handleSubmitOwn = (e) => handleEntitySubmit(e, "owns", ownForm, currentOwnId, ownMode, resetOwnForm);

  // Création/Mise à jour power
  const handleSubmitPower = (e) => handleEntitySubmit(e, "powers", powerForm, currentPowerId, powerMode, resetPowerForm);
  
  // Suppression d'un élément
  const handleDeleteItem = async (item) => {
    if (!item || !item.id) {
      alert("Impossible de supprimer cet élément : ID manquant");
      return;
    }
    
    if (!window.confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
      return;
    }
    
    try {
      await Api.entityOperation(currentEntityType, "delete", null, item.id);
      alert("Élément supprimé avec succès !");
      // Rafraîchir la liste
      handleViewAll(currentEntityType);
    } catch (err) {
      alert("Erreur lors de la suppression");
      console.error("Erreur:", err);
    }
  };

  return (
    <>
      <Header />
      <div className="dashboard-container">
        <div className="dashboard-grid dashboard-grid-3">
          <div>
            {/* Cars */}
            <section className="dashboard-section">
              <h2>Voiture</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('cars')}>Voir tout</button>
                <button 
                  className={carMode === 'create' ? 'active' : ''} 
                  onClick={() => resetCarForm()}
                >
                  {carMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={carMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (carMode === 'update') resetCarForm();
                    else handleViewAll('cars');
                  }}
                >
                  {carMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitCar}>
                <input type="text" placeholder="Marque" name="brand" value={carForm.brand} onChange={handleCarChange} />
                <input type="text" placeholder="Modèle" name="model" value={carForm.model} onChange={handleCarChange} />
                <input type="text" placeholder="Année" name="year" value={carForm.year} onChange={handleCarChange} />
                <input type="text" placeholder="Couleur" name="color" value={carForm.color} onChange={handleCarChange} />
                <input type="text" placeholder="Génération" name="generation" value={carForm.generation} onChange={handleCarChange} />
                <input type="text" placeholder="Image URL" name="image_url" value={carForm.image_url} onChange={handleCarChange} />
                <input type="text" placeholder="Edition ID" name="edition_id" value={carForm.edition_id} onChange={handleCarChange} />
                <button type="submit">{carMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
            {/* Editions */}
            <section className="dashboard-section">
              <h2>Édition</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('editions')}>Voir tout</button>
                <button 
                  className={editionMode === 'create' ? 'active' : ''} 
                  onClick={() => resetEditionForm()}
                >
                  {editionMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={editionMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (editionMode === 'update') resetEditionForm();
                    else handleViewAll('editions');
                  }}
                >
                  {editionMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitEdition}>
                <input type="text" placeholder="Nom de l'édition" name="edition_name" value={editionForm.edition_name} onChange={handleEditionChange} />
                <button type="submit">{editionMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
          </div>
          <div>
            {/* Engines */}
            <section className="dashboard-section">
              <h2>Moteur</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('engines')}>Voir tout</button>
                <button 
                  className={engineMode === 'create' ? 'active' : ''} 
                  onClick={() => resetEngineForm()}
                >
                  {engineMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={engineMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (engineMode === 'update') resetEngineForm();
                    else handleViewAll('engines');
                  }}
                >
                  {engineMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitEngine}>
                <input type="text" placeholder="Nom moteur" name="engine_name" value={engineForm.engine_name} onChange={handleEngineChange} />
                <input type="text" placeholder="Architecture" name="architecture" value={engineForm.architecture} onChange={handleEngineChange} />
                <input type="text" placeholder="Volume" name="volume" value={engineForm.volume} onChange={handleEngineChange} />
                <input type="text" placeholder="Induction" name="induction" value={engineForm.induction} onChange={handleEngineChange} />
                <input type="text" placeholder="Carburant" name="fuel_type" value={engineForm.fuel_type} onChange={handleEngineChange} />
                <button type="submit">{engineMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
            {/* Motorizations */}
            <section className="dashboard-section">
              <h2>Motorisation</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('motorizations')}>Voir tout</button>
                <button 
                  className={motoMode === 'create' ? 'active' : ''} 
                  onClick={() => resetMotoForm()}
                >
                  {motoMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={motoMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (motoMode === 'update') resetMotoForm();
                    else handleViewAll('motorizations');
                  }}
                >
                  {motoMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitMoto}>
                <input type="text" placeholder="Couple" name="torque" value={motoForm.torque} onChange={handleMotoChange} />
                <input type="text" placeholder="Puissance" name="power" value={motoForm.power} onChange={handleMotoChange} />
                <input type="text" placeholder="Consommation" name="consumption" value={motoForm.consumption} onChange={handleMotoChange} />
                <input type="text" placeholder="Engine ID" name="engine_id" value={motoForm.engine_id} onChange={handleMotoChange} />
                <button type="submit">{motoMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
          </div>          <div>
            {/* Users - visible uniquement pour les superAdmin */}
            {isSuperAdmin && (
              <section className="dashboard-section">
                <h2>Utilisateur</h2>
                <div className="dashboard-actions">
                  <button onClick={() => handleViewAll('users')}>Voir tout</button>
                  <button 
                    className={userMode === 'create' ? 'active' : ''} 
                    onClick={() => resetUserForm()}
                  >
                    {userMode === 'create' ? '✓ Créer' : 'Créer'}
                  </button>
                  <button 
                    className={userMode === 'update' ? 'active' : ''} 
                    onClick={() => {
                      if (userMode === 'update') resetUserForm();
                      else handleViewAll('users');
                    }}
                  >
                    {userMode === 'update' ? '✓ Update' : 'Update'}
                  </button>
                </div>
                <form className="dashboard-form" onSubmit={handleSubmitUser}>
                  <input type="text" placeholder="Prénom" name="first_name" value={userForm.first_name} onChange={handleUserChange} />
                  <input type="text" placeholder="Nom" name="last_name" value={userForm.last_name} onChange={handleUserChange} />
                  <input type="date" placeholder="Date de naissance" name="date_of_birth" value={userForm.date_of_birth} onChange={handleUserChange} />
                  <input type="email" placeholder="Email" name="email" value={userForm.email} onChange={handleUserChange} />
                  <input type="password" placeholder={userMode === 'update' ? "Nouveau mot de passe (vide = inchangé)" : "Mot de passe"} name="password" value={userForm.password} onChange={handleUserChange} />
                  <select name="role" value={userForm.role} onChange={handleUserChange}>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="superAdmin">SuperAdmin</option>
                  </select>
                  <button type="submit">{userMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
                </form>
              </section>
            )}
            {/* Own */}
            <section className="dashboard-section">
              <h2>Possession</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('owns')}>Voir tout</button>
                <button 
                  className={ownMode === 'create' ? 'active' : ''} 
                  onClick={() => resetOwnForm()}
                >
                  {ownMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={ownMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (ownMode === 'update') resetOwnForm();
                    else handleViewAll('owns');
                  }}
                >
                  {ownMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitOwn}>
                <input type="text" placeholder="Car ID" name="car_id" value={ownForm.car_id} onChange={handleOwnChange} />
                <input type="text" placeholder="User ID" name="user_id" value={ownForm.user_id} onChange={handleOwnChange} />
                <button type="submit">{ownMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
            {/* Power */}
            <section className="dashboard-section">
              <h2>Motorisation d'une voiture par un moteur</h2>
              <div className="dashboard-actions">
                <button onClick={() => handleViewAll('powers')}>Voir tout</button>
                <button 
                  className={powerMode === 'create' ? 'active' : ''} 
                  onClick={() => resetPowerForm()}
                >
                  {powerMode === 'create' ? '✓ Créer' : 'Créer'}
                </button>
                <button 
                  className={powerMode === 'update' ? 'active' : ''} 
                  onClick={() => {
                    if (powerMode === 'update') resetPowerForm();
                    else handleViewAll('powers');
                  }}
                >
                  {powerMode === 'update' ? '✓ Update' : 'Update'}
                </button>
              </div>
              <form className="dashboard-form" onSubmit={handleSubmitPower}>
                <input type="text" placeholder="Car ID" name="car_id" value={powerForm.car_id} onChange={handlePowerChange} />
                <input type="text" placeholder="Engine ID" name="engine_id" value={powerForm.engine_id} onChange={handlePowerChange} />
                <button type="submit">{powerMode === 'update' ? 'Mettre à jour' : 'Valider'}</button>
              </form>
            </section>
          </div>
        </div>        <ViewAllModal 
          open={modalOpen} 
          onClose={() => setModalOpen(false)} 
          title={modalTitle} 
          data={modalData} 
          columns={modalColumns} 
          onSelectForUpdate={handleSelectForUpdate}
          onDelete={handleDeleteItem}
        />
      </div>
      <Footer />
    </>
  );
}

export default Dashboard;
