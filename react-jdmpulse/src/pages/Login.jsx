import React, { useState } from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import { useNavigate } from "react-router-dom";
import "../styles/Login.css";
import Api from "../functions/Api";

function Login() {
	const navigate = useNavigate();
	const [formData, setFormData] = useState({
		email: "",
		password: ""
	});
	const [error, setError] = useState("");
	const [loading, setLoading] = useState(false);

	const handleChange = (e) => {
		const { id, value } = e.target;
		setFormData(prevState => ({
			...prevState,
			[id]: value
		}));
	};

	const handleSubmit = async (e) => {
		e.preventDefault();
		setError("");
		setLoading(true);

		try {
			const response = await Api("POST", "auth/login", {
				email: formData.email,
				password: formData.password
			}, "", false, {});

			if (response && response.status === 200 && response.body && response.body.data && response.body.data.access_token) {
				// Stocke le token dans le sessionStorage
				sessionStorage.setItem("bearer", response.body.data.access_token);
				// Stocke également les informations de l'utilisateur si nécessaire
				sessionStorage.setItem("user", JSON.stringify(response.body.data));
				navigate("/discover");
			} else if (response && response.body && response.body.message) {
				setError(response.body.message);
			} else {
				setError("Structure de réponse inattendue");
				console.error("Réponse complète:", response.body);
			}
		} catch (error) {
			setError("Une erreur est survenue lors de la connexion.");
			console.error("Erreur de connexion:", error);
		} finally {
			setLoading(false);
		}
	};

	const handleRegisterClick = () => {
		navigate("/register");
	};

	const goBack = () => {
		navigate("/connection");
	};

	return (
		<div>
			<Header />
			<div className="login-container">
				<div className="login-card">
					<div className="login-header">
						<button onClick={goBack} className="btn-back"></button>
						<h1>Login</h1>
					</div>
					<div className="login-form">
						{error && <div className="error-message">{error}</div>}
						<form onSubmit={handleSubmit}>
							<div className="form-group">
								<label htmlFor="email">Email</label>
								<input 
									type="email" 
									id="email" 
									placeholder="Email" 
									value={formData.email}
									onChange={handleChange}
									required
								/>
							</div>
							<div className="form-group">
								<label htmlFor="password">Password</label>
								<input 
									type="password" 
									id="password" 
									placeholder="Password" 
									value={formData.password}
									onChange={handleChange}
									required
								/>
							</div>
							<div className="forgot-password">
								<a href="#">Forgot password?</a>
							</div>
							<div className="login-actions">
								<button type="submit" disabled={loading}>
									{loading ? "Connexion..." : "Submit"}
								</button>
								<button type="button" onClick={handleRegisterClick} disabled={loading}>
									Not yet registered?
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<Footer />
		</div>
	);
}

export default Login;