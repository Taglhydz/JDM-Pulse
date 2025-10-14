import React from "react";
import { useNavigate } from "react-router-dom";
import "../styles/Connection.css";
import Header from "../components/Header";
import Footer from "../components/Footer";

function Connection() {
	const navigate = useNavigate();

	const goLogin = () => {
		navigate("/login");
	}
	
	const goRegister = () => {
		navigate("/register");
	}

	return (
		<div>
			<Header />
			<div className="connection-container">
				<div className="connection">
					<h1>Connection</h1>
					<p>What do you choose ?</p>
					<div className="connection-options">
						<button onClick={goLogin}>Sign in</button>
						<button onClick={goRegister}>Sign up</button>
					</div>
				</div>
			</div>
			<Footer />
		</div>
	);
}

export default Connection;