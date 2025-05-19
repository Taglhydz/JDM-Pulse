import React from "react";
import Header from "../components/Header";
import Footer from "../components/Footer";
import { useNavigate } from "react-router-dom";
import "../styles/Register.css";

function Register() {
	const navigate = useNavigate();

	const handleLoginClick = () => {
		navigate("/login");
	};

	const goBack = () => {
		navigate("/connection");
	}

	return (
		<div>
			<Header />
			<div className="register-container">
				<div className="register-card">
					<div className="register-header">
						<button onClick={goBack} className="btn-back"></button>
						<h1>Register</h1>
					</div>
					<div className="register-form">
						<form>
							<div className="form-row">
								<div className="form-group">
									<label htmlFor="firstName">First name</label>
									<input type="text" id="firstName" placeholder="First name"/>
								</div>
								<div className="form-group">
									<label htmlFor="lastName">Last name</label>
									<input type="text" id="lastName" placeholder="Last name"/>
								</div>
							</div>
							<div className="form-group">
								<label htmlFor="dob">Date of birth</label>
								<input type="date" id="dob" placeholder="Date of birth"/>
							</div>
							<div className="form-group">
								<label htmlFor="email">Email</label>
								<input type="email" id="email" placeholder="Email"/>
							</div>
							<div className="form-group">
								<label htmlFor="password">Password</label>
								<input type="password" id="password" placeholder="Password"/>
							</div>
							<div className="form-group">
								<label htmlFor="confirmPassword">Confirm Password</label>
								<input type="password" id="confirmPassword" placeholder="Confirm password"/>
							</div>
							<div className="register-actions">
								<button type="submit">Submit</button>
								<button type="button" onClick={handleLoginClick}>Already registered?</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<Footer />
		</div>
	);
}

export default Register;