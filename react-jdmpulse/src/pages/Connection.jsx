import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../styles/Connection.css";
import Header from "../components/Header";
import Footer from "../components/Footer";
import { loginDemo } from "../functions/Session";

function Connection() {
	const navigate = useNavigate();
	const [demoLoading, setDemoLoading] = useState(false);
	const [error, setError] = useState("");

	const goDemo = async () => {
		setError("");
		setDemoLoading(true);
		if (await loginDemo()) {
			navigate("/discover");
		} else {
			setError("Le compte démo est indisponible pour le moment.");
		}
		setDemoLoading(false);
	}

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
					<div className="connection-separator"><span>or</span></div>
					{error && <div className="error-message">{error}</div>}
					<div className="guest-options">
						<button onClick={goDemo} disabled={demoLoading}>
							{demoLoading ? "Connecting..." : "Try the demo account"}
						</button>
					</div>
				</div>
			</div>
			<Footer />
		</div>
	);
}

export default Connection;