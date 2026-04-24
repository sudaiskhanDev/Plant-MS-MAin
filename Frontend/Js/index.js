 

// ✅ check login state on page load
document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem("token");

    if (token) {
        // user logged in
        document.getElementById("guestButtons").style.display = "none";
        document.getElementById("userButtons").style.display = "block";
    } else {
        // guest
        document.getElementById("guestButtons").style.display = "block";
        document.getElementById("userButtons").style.display = "none";
    }
});


// ✅ LOGOUT FUNCTION (backend connected)
async function logout() {

    const token = localStorage.getItem("token");

    if (!token) {
        alert("Already logged out");
        return;
    }

    try {
        let res = await fetch('http://127.0.0.1:8000/api/user/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        // even if backend fails → logout frontend anyway
        localStorage.removeItem("token");

        // UI update
        document.getElementById("guestButtons").style.display = "block";
        document.getElementById("userButtons").style.display = "none";

        // redirect to home
        window.location.href = "index.html";

    } catch (error) {
        console.log(error);

        // fallback logout
        localStorage.removeItem("token");
        window.location.href = "index.html";
    }
}
 