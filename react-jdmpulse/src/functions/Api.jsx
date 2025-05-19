async function Api(method, route, body, params, token) {
    const port = "8000"

    if (method === "POST") {
        let head = {
            "Content-type": "application/json; charset=UTF-8",
        };
        if (token) {
            const bearer = sessionStorage.getItem("bearer");
            head["Authorization"] = `Bearer ${bearer}`;
        }
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
    }
    if (method === "GET") {
        let head = {
            "Content-type": "application/json; charset=UTF-8",
        };
        if (token) {
            const bearer = sessionStorage.getItem("bearer");
            head["Authorization"] = `Bearer ${bearer}`;
        }

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
    } else {
        throw new Error("Méthode non supportée");
    }
}
export default Api