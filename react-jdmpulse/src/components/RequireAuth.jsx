import React from "react";
import { Navigate } from "react-router-dom";
import { getUser } from "../functions/Session";

// Protège une page : un visiteur non connecté est renvoyé vers la page de connexion
function RequireAuth({ children }) {
    if (!getUser()) return <Navigate to="/connection" />;
    return children;
}

export default RequireAuth;
