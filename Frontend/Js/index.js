 

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
 



 
const API = "http://127.0.0.1:8000/api/plants";

loadLatestProducts();

async function loadLatestProducts() {

    const res = await fetch(API);
    const data = await res.json();

    const container = document.getElementById("latestProducts");
    const template = document.getElementById("productTemplate");

    container.innerHTML = ""; // clear

    // latest 4 products
    const latest = data.slice(-4).reverse();

    latest.forEach(p => {

        let clone = template.cloneNode(true);
        clone.style.display = "block";
        clone.removeAttribute("id");

        // DATA SET
        clone.querySelector(".product-name").textContent = p.name;
        clone.querySelector(".product-price").textContent = "$" + p.price;

        let img = p.image 
            ? "http://127.0.0.1:8000/storage/" + p.image 
            : "https://via.placeholder.com/200";

        clone.querySelector(".product-img").src = img;

        // 🔥 CLICK → DETAILS PAGE
        clone.style.cursor = "pointer";
        clone.onclick = () => {
            window.location.href = "./Pages/Product details.html?id=" + p.plant_id;
        };

        container.appendChild(clone);
    });
}
 