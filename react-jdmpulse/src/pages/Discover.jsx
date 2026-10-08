import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import Api 	  from "../functions/Api";
import CarDetailModal from "../components/CarDetailModal";
import "../styles/Discover.css";
import "../styles/HeartButton.css";

function Discover() {
	const [cars, 		setCars		  ] = useState([]);
	const [selectedCar, setSelectedCar] = useState(null);
	const [userLikes, 	setUserLikes  ] = useState([]);
	const [likesCount, 	setLikesCount ] = useState({});
	const [loading, 	setLoading	  ] = useState(true);
	const access_token = sessionStorage.getItem("bearer");

	useEffect(() => {
		const fetchData = async () => {
			try {
				setLoading(true);

				const carsResponse = await Api("GET", "cars/all", null, "", access_token);
				if (carsResponse && carsResponse.status === 200 && carsResponse.body && carsResponse.body.data) {
					setCars(carsResponse.body.data);
					console.log("Cars data:", carsResponse.body.data);
				}

				const userId = JSON.parse(sessionStorage.getItem("user")).id;
				const userLikesResponse = await Api("GET", `likes-by-cars-user/${userId}`, null, "", true);
				if (userLikesResponse.status === 200 && userLikesResponse.body.success) {
					const likes = userLikesResponse.body.data;
					// tableau id des voitures likées par l'user
					const likedCarIds = likes.map(like => like.car_id);
					setUserLikes(likedCarIds);
					console.log("User likes:", likedCarIds);
				}

				// récupére le nombre de likes pour chaque car
				const likesCountResponse = await Api("GET", "likes-by-car", null, "", true);
				if (likesCountResponse.status === 200 && likesCountResponse.body.success) {
					const likesData = likesCountResponse.body.data;
					const countMap = {};
					
					likesData.forEach(item => {
						countMap[item.car_id] = item.likes_count;
					});
					
					setLikesCount(countMap);
					console.log("Likes count:", countMap);
				}

				setLoading(false);
			} catch (error) {
				console.error("Erreur lors de la récupération des données:", error);
				setLoading(false);
			}
		};

		fetchData();
	}, [access_token]);

	const handleCardClick = (car) => {
		setSelectedCar(car);
	};

	const handleCloseModal = () => {
		setSelectedCar(null);
	};
	const handleLike = async (carId, event) => {
		event.stopPropagation();
		try {
			const userId = JSON.parse(sessionStorage.getItem("user")).id;
			
			if (userLikes.includes(carId)) {
				setUserLikes(prevLikes => prevLikes.filter(id => id !== carId));
				setLikesCount(prev => ({
					...prev,
					[carId]: Math.max((prev[carId] || 1) - 1, 0)
				}));
				
				// si la voiture est déjà likée -> unlike
				const response = await Api("POST", "unlike", { user_id: userId, car_id: carId }, `/${carId}`, true);
				
				if (!response.status === 200 || !response.body.success) {
					setUserLikes(prevLikes => [...prevLikes, carId]);
					setLikesCount(prev => ({
						...prev,
						[carId]: (prev[carId] || 0) + 1
					}));
					console.error("Erreur lors du unlike");
				}
			} else {
				setUserLikes(prevLikes => [...prevLikes, carId]);
				setLikesCount(prev => ({
					...prev,
					[carId]: (prev[carId] || 0) + 1
				}));
				
				const response = await Api("POST", "like", { user_id: userId, car_id: carId }, `/${carId}`, true);
				
				if (!response.status === 200 || !response.body.success) {
					setUserLikes(prevLikes => prevLikes.filter(id => id !== carId));
					setLikesCount(prev => ({
						...prev,
						[carId]: Math.max((prev[carId] || 1) - 1, 0)
					}));
					console.error("Erreur lors du like");
				}
			}
		} catch (error) {
			console.error("Erreur lors de l'action de like/unlike:", error);
		}
	};

	return (
		<>
			<Header />
			<div className="discover-page">
				<h1>Découvrir des JDM</h1>
				{loading ? (
					<div className="loading">Chargement...</div>
				) : (
					<div className="cars-list">
						{cars.length === 0 ? (
							<p>Aucune voiture disponible.</p>
						) : (							cars.map(car => (
								<div className="car-card" key={car.id} onClick={() => handleCardClick(car)}>
									<button 
										className="heart-button heart-button-plain"
										onClick={(e) => handleLike(car.id, e)}
									>
										<span className={`heart-icon ${userLikes.includes(car.id) ? 'liked' : ''}`}>
											{userLikes.includes(car.id) ? '❤️' : '🤍'}
										</span>
									</button>
									<img src={car.image_url} alt={`${car.brand} ${car.model}`} style={{width:'100%',maxWidth:'300px',borderRadius:'8px'}} />
									<h2>{car.brand} {car.model}</h2>
									<p>Année : {car.year}</p>
									<p>Génération : {car.generation}</p>
									<p>Couleur : {car.color}</p>
									<p>❤️ {likesCount[car.id] || 0} likes</p>
								</div>
							))
						)}
					</div>
				)}
				<CarDetailModal car={selectedCar} onClose={handleCloseModal} />
			</div>
			<Footer />
		</>
	);
}

export default Discover;
