import React from 'react';
import "../styles/Footer.css"

function Footer() {
	return <footer>
		<div className="footer-container">
			<div className="footer-brand">
				<h1>JDM - <br/>Pulse</h1>
			</div>
			<div className="footer-copyright">
				<p>Copyright 2025 © all rights reserved</p>
			</div>
			<div className="footer-links">
				<a href="https://github.com/Taglhydz/JDM-Pulse" className="github-link">Github</a>
			</div>
		</div>
	</footer>
}
export default Footer;