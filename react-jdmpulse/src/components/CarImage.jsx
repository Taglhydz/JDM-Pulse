import React from "react";

const PLACEHOLDER = `${process.env.PUBLIC_URL}/placeholder-car.svg`;

// Image de voiture : affiche un placeholder si l'URL est vide ou si l'image ne charge pas (lien mort)
function CarImage({ src, alt, ...props }) {
    const handleError = (e) => {
        // évite une boucle si le placeholder lui-même ne charge pas
        if (!e.currentTarget.src.endsWith("placeholder-car.svg")) {
            e.currentTarget.src = PLACEHOLDER;
        }
    };

    return <img src={src || PLACEHOLDER} alt={alt} onError={handleError} {...props} />;
}

export default CarImage;
