import React, { useState, useEffect } from "react";
import CarDetailModal from "../components/CarDetailModal";
import Header         from "../components/Header";
import Footer         from "../components/Footer";
import Api            from "../functions/Api";
import CarImage       from "../components/CarImage";
import "../styles/Liked.css";
import "../styles/HeartButton.css";

function Liked() {
  const [likedCars,   setLikedCars  ] = useState([]);
  const [likesCount,  setLikesCount ] = useState({});
  const [loading,     setLoading    ] = useState(true);
  const [error,       setError      ] = useState(null);
  const [selectedCar, setSelectedCar] = useState(null);

  useEffect(() => {
    const fetchLikedCars = async () => {
      try {
        setLoading(true);
        const userId = JSON.parse(sessionStorage.getItem("user")).id;
        
        // récupére les voitures likés par l'user
        const likedResponse = await Api("GET", `likes-by-cars-user/${userId}`, null, "", true);
        
        if (likedResponse.status === 200 && likedResponse.body.success) {
          const likes = likedResponse.body.data;
          
          // chaque voiture likée -> récupérer les détails
          const carsWithDetails = await Promise.all(
            likes.map(async (like) => {
              const carResponse = await Api("GET", `cars/${like.car_id}`, null, "", true);
              if (carResponse.status === 200 && carResponse.body.success) {
                return carResponse.body.data;
              }
              return null;
            })
          );
          
          setLikedCars(carsWithDetails.filter(car => car !== null));
        } else {
          setError("Impossible de récupérer les voitures likées");
        }
        
        // récupére le nombre de likes de chaque car
        const likesCountResponse = await Api("GET", "likes-by-car", null, "", true);
        if (likesCountResponse.status === 200 && likesCountResponse.body.success) {
          const likesData = likesCountResponse.body.data;
          const countMap = {};
          
          likesData.forEach(item => {
            countMap[item.car_id] = item.likes_count;
          });
          
          setLikesCount(countMap);
          console.log("Likes data:", likesData);
          console.log("Likes count:", countMap);
        }
        
        setLoading(false);
      } catch (error) {
        console.error("Erreur lors de la récupération des voitures likées:", error);
        setError("Une erreur est survenue lors du chargement des données");
        setLoading(false);
      }
    };

    fetchLikedCars();
  }, []);
  const handleUnlike = async (carId, event) => {
    event.stopPropagation();
    try {
      const userId = JSON.parse(sessionStorage.getItem("user")).id;
      
      setLikedCars(prevCars => prevCars.filter(car => car.id !== carId));
      
      setLikesCount(prev => ({
        ...prev,
        [carId]: Math.max((prev[carId] || 1) - 1, 0)
      }));
      
      const response = await Api("POST", "unlike", { user_id: userId, car_id: carId }, `/${carId}`, true);
      
      if (!response.status === 200 || !response.body.success) {
        const carResponse = await Api("GET", `cars/${carId}`, null, "", true);
        if (carResponse.status === 200 && carResponse.body.success) {
          setLikedCars(prevCars => [...prevCars, carResponse.body.data]);
          
          setLikesCount(prev => ({
            ...prev,
            [carId]: (prev[carId] || 0) + 1
          }));
        }
        console.error("Erreur lors du unlike");
      }
    } catch (error) {
      console.error("Erreur lors du unlike:", error);
    }
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
      <div className="liked-page">
        <h1>Mes voitures likées</h1>
        
        {loading ? (
          <div className="loading">Chargement...</div>
        ) : error ? (
          <div className="error">{error}</div>
        ) : likedCars.length === 0 ? (
          <div className="no-likes">Vous n'avez pas encore liké de voitures</div>
        ) : (
          <div className="cars-list">            {likedCars.map(car => (
              <div key={car.id} className="car-card" onClick={() => handleCardClick(car)}>
                <button 
                  className="heart-button heart-button-plain" 
                  onClick={(e) => handleUnlike(car.id, e)}
                >
                  <span className="heart-icon">❤️</span>
                </button>
                <CarImage 
                  src={car.image_url} 
                  alt={`${car.brand} ${car.model}`} 
                  style={{width:'100%', maxWidth:'300px', borderRadius:'8px'}} 
                />
                <h2>{car.brand} {car.model}</h2>
                <p>Année : {car.year}</p>
                <p>Génération : {car.generation || "N/A"}</p>
                <p>Couleur : {car.color || "N/A"}</p>
                <p>❤️ {likesCount[car.id] || 0} likes</p>
              </div>
            ))}
          </div>
        )}
        <CarDetailModal car={selectedCar} onClose={handleCloseModal} />
      </div>
      <Footer />
    </>
  );
}

export default Liked;
