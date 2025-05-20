import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import { useNavigate } from "react-router-dom";
import "../styles/Discover.css";
import Api from "../functions/Api";
import CarDetailModal from "../components/CarDetailModal";

function Discover() {
	const navigate = useNavigate();
	const [cars, setCars] = useState([]);
	const [selectedCar, setSelectedCar] = useState(null);
	const access_token = sessionStorage.getItem("bearer");

	useEffect(() => {
		Api("GET", "cars/all", null, "", access_token, {'API-Key': 'Miam0Tacos!'})
			.then(response => {
				if (response && response.status === 200 && response.body && response.body.data) {
					setCars(response.body.data);
				}
			})
			.catch(error => console.error(error));
	}, []);

	const handleCardClick = (car) => {
		setSelectedCar(car);
	};

	const handleCloseModal = () => {
		setSelectedCar(null);
	};

	return (
		<div className="discover-page">
			<Header />
			<h1>Découvrir des JDM</h1>
			<div className="cars-list">
				{cars.length === 0 ? (
					<p>Aucune voiture disponible.</p>
				) : (
					cars.map(car => (
						<div className="car-card" key={car.id} onClick={() => handleCardClick(car)}>
							<img src={car.image_url} alt={`${car.brand} ${car.model}`} style={{width:'100%',maxWidth:'300px',borderRadius:'8px'}} />
							<h2>{car.brand} {car.model}</h2>
							<p>Année : {car.year}</p>
							<p>Génération : {car.generation}</p>
							<p>Couleur : {car.color}</p>
						</div>
					))
				)}
			</div>
			<CarDetailModal car={selectedCar} onClose={handleCloseModal} />
			<Footer />
		</div>
	);
}

export default Discover;
