import React from "react";
import { Link } from "react-router-dom";
import "../styles/Header.css";
import { useLocation, useNavigate } from "react-router-dom";
import { getUser, logout } from "../functions/Session";

function Header() {
	const location = useLocation();
	const navigate = useNavigate();
	const user = getUser();
	const isAdmin = user && (user.role === "admin" || user.role === "superAdmin");

	const handleLogout = async () => {
		await logout();
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
		<>
			<header>
				{isAdmin && (
					<button className="dashboard-btn" onClick={() => navigate('/dashboard')}>Dashboard</button>
				)}
				<ul id='nav'>
					{user && (
						<li>
							<Link to="/Profile">Profile</Link>
						</li>
					)}

					<li>
						<Link to="/Discover">Discover</Link>
					</li>

					<li>
						<h1>JDM - Pulse</h1>
					</li>
					{user && (
						<>
							<li>
								<Link to="/Liked">Liked</Link>
							</li>
							<li>
								<Link to="/Add">Add</Link>
							</li>
						</>
					)}
				</ul>
				{user ? (
					<button className="logout-btn" onClick={handleLogout}>Logout</button>
				) : (
					<button className="logout-btn" onClick={() => navigate("/connection")}>Sign in</button>
				)}
			</header>
			<div className="header-divider"></div>
		</>
	);
}

export default Header;