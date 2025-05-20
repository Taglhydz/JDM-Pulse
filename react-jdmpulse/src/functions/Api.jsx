async function Api(method, route, body, params, token, headers = {}) {
    const port = "8000"

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

            const response = await fetch(`http://127.0.0.1:${port}/api/${route}${params}`, {
                method: "POST",
                body: JSON.stringify(body),
                headers: head
            });

            const data = await response.json();  

            if (response.ok && response.status === 200) {
                return { status: response.status, body: data };
            } else { 
                console.error("Erreur lors de la connexion");
            }
        } catch (error) {
            console.error("Une erreur est survenue : ", error);
        }
        return;
    }
    if (method === "GET") {
        try {
            const response = await fetch(`http://localhost:${port}/api/${route}${params}`, {
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
        return;
    }
    throw new Error("Méthode non supportée");
}
export default Api