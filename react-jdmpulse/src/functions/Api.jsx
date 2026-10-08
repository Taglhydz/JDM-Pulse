// URL de l'API définie dans .env (REACT_APP_API_URL), localhost par défaut en dev
const API_URL = process.env.REACT_APP_API_URL || "http://localhost:8000";

async function Api(method, route, body, params, token, headers = {}) {

    let head = {
        "Accept": "application/json",
        "Content-Type": "application/json; charset=UTF-8",
        ...headers
    };
    if (token && !head["Authorization"]) {
        const bearer = sessionStorage.getItem("bearer");
        head["Authorization"] = `Bearer ${bearer}`;
    }

    if (method === "POST") {
        try {
            const response = await fetch(`${API_URL}/api/${route}${params}`, {
                method: "POST",
                body: JSON.stringify(body),
                headers: head
            });

            const data = await response.json();  

            console.log("Réponse API : ", data);

            if (data && data.success) {
                return { status: response.status, body: data };
            } else { 
                console.log("Erreur response : ", data);
                console.error("Erreur lors de la connexion");
                return { status: response.status, body: data };
            }
        } catch (error) {
            console.error("Une erreur est survenue : ", error);
            return { status: 500, message: error.message };
        }
    }
    if (method === "GET") {
        try {
            const response = await fetch(`${API_URL}/api/${route}${params}`, {
                method: "GET",
                headers: head,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();

            return { status: response.status, body: data }; 
        } catch (error) {
            console.error("Une erreur est survenue:", error);
            return { status: error.status, message: error.message }; 
        }
    }
    if (method === "DELETE") {
        try {
            const response = await fetch(`${API_URL}/api/${route}${params}`, {
                method: "DELETE",
                headers: head,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return { status: response.status, body: data }; 
        } catch (error) {
            console.error("Une erreur est survenue lors de la suppression:", error);
            return { status: error.status, message: error.message }; 
        }
    }
    throw new Error("Méthode non supportée");
}

// fonction générique pour gérer les CRUD
Api.entityOperation = async function(entityType, operation, data, id = null) {
    const token = sessionStorage.getItem("bearer");
    
    try {
        let route, method;
        
        switch (operation) {
            case 'create':
                route = `${entityType}/create`;
                method = 'POST';
                break;
            case 'update':
                route = `${entityType}/update/${id}`;
                method = 'POST';
                break;
            case 'delete':
                route = `${entityType}/delete/${id}`;
                method = 'DELETE';
                break;
            case 'get':
                route = `${entityType}/${id}`;
                method = 'GET';
                break;
            case 'getAll':
                route = `${entityType}/all`;
                method = 'GET';
                break;
            default:
                throw new Error(`Opération ${operation} non supportée`);
        }
        
        const response = await Api(method, route, data, "", token);
        return response;
    } catch (error) {
        console.error(`Erreur lors de l'opération ${operation} sur ${entityType}:`, error);
        throw error;
    }
};

// fonction générique pour gérer les form
Api.handleEntitySubmit = async function(entityType, formData, currentId, mode, options = {}) {
    try {
        const operation = mode === "update" && currentId ? "update" : "create";
        
        if (entityType === "users" && operation === "update" && formData.password === "") {
            const dataToSend = {...formData};
            delete dataToSend.password;
            await this.entityOperation(entityType, operation, dataToSend, currentId);
        } else {
            await this.entityOperation(entityType, operation, formData, currentId);
        }
        
        const entityLabel = 
            entityType === "users" ? "Utilisateur" : 
            entityType === "cars" ? "Voiture" :
            entityType === "engines" ? "Moteur" :
            entityType === "editions" ? "Édition" :
            entityType === "motorizations" ? "Motorisation" :
            entityType === "owns" ? "Possession" :
            entityType === "powers" ? "Relation voiture-moteur" : "Élément";
            
        const actionLabel = operation === "update" ? "mis à jour" : "créé";
        
        return {
            success: true,
            message: `${entityLabel} ${actionLabel} avec succès !`
        };
    } catch (err) {
        console.error("Erreur:", err);
        return {
            success: false,
            message: `Erreur lors de ${mode === "update" ? "la mise à jour" : "la création"}`,
            error: err
        };
    }
};

export default Api