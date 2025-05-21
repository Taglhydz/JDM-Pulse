import React from "react";
import { Link } from "react-router-dom";
import "../styles/Header.css";
import { useLocation, useNavigate } from "react-router-dom";

function Header() {
	const location = useLocation();
	const navigate = useNavigate();
	const userData = sessionStorage.getItem("user");
	const user = userData ? JSON.parse(userData) : null;
	const isAdmin = user && (user.role === "admin" || user.role === "superAdmin");

	const handleLogout = () => {
		sessionStorage.removeItem("access_token");
		sessionStorage.removeItem("user");
		navigate("/connection");
	};

	if (location.pathname === "/connection" || location.pathname === "/register" || location.pathname === "/login") {
		return (
			<header>
				<h1>JDM - Pulse</h1>
			</header>
		);
	}

	return (
		<header>
			{isAdmin && (
				<button className="dashboard-btn" onClick={() => navigate('/dashboard')}>Dashboard</button>
			)}
			<ul id='nav'>
				<li>
					<Link to="/Profile">Profile</Link>
				</li>

				<li>
					<Link to="/Discover">Discover</Link>
				</li>

				<li>
					<h1>JDM - Pulse</h1>
				</li>
				<li>
					<Link to="/Liked">Liked</Link>
				</li>
				<li>
					<Link to="/Add">Add</Link>
				</li>
			</ul>
			<button className="logout-btn" onClick={handleLogout}>Logout</button>
		</header>
	);
}
export default Header;