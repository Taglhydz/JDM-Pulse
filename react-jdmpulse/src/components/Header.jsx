import React from "react";
import { Link } from "react-router-dom";
import "../styles/Header.css";
import { useLocation } from "react-router-dom";

function Header() {
	const location = useLocation();

	if (location.pathname === "/connection" || location.pathname === "/register" || location.pathname === "/login") {
		return (
			<header>
				<h1>JDM - Pulse</h1>
			</header>
		);
	}

	return (
		<header>
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
		</header>
	);
}
export default Header;