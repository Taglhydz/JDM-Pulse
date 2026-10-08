import Api from "./Api";

// Utilisateur connecté (null pour un visiteur)
export function getUser() {
    const userData = sessionStorage.getItem("user");
    return userData ? JSON.parse(userData) : null;
}

// Stocke le token et les infos de l'user après une connexion réussie
export function saveSession(data) {
    sessionStorage.setItem("bearer", data.access_token);
    sessionStorage.setItem("user", JSON.stringify(data));
}

// Connexion au compte démo, renvoie true si elle a réussi
export async function loginDemo() {
    const response = await Api("POST", "auth/demo", {}, "", false);
    if (response && response.status === 200 && response.body && response.body.data) {
        saveSession(response.body.data);
        return true;
    }
    return false;
}

// Révoque le token côté API puis vide la session
export async function logout() {
    if (sessionStorage.getItem("bearer")) {
        await Api("GET", "logout", null, "", true);
    }
    sessionStorage.removeItem("bearer");
    sessionStorage.removeItem("user");
}
