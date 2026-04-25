 
async function logout() {
    const token = localStorage.getItem("token");

    try {
        const response = await fetch("http://127.0.0.1:8000/api/auth/logout", {
            method: "POST",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            }
        });

        const data = await response.json();

        // clear storage no matter what
        localStorage.removeItem("token");
        localStorage.removeItem("role");

        // redirect to login page
        window.location.href = "../../login.html";

    } catch (error) {
        console.log(error);

        // still force logout locally
        localStorage.removeItem("token");
        localStorage.removeItem("role");

        window.location.href = "../../login.html";
    }
}
 