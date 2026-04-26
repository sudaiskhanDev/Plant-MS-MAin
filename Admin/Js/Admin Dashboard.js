 
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
 









 
    // API base
    const BASE = "http://127.0.0.1:8000/api";

    

    async function loadDashboard() {
        try {
            const [plants, categories, orders, payments, maintenances, notifications, suppliers, staffRes] = await Promise.all([
                fetch(`${BASE}/plants`).then(r => r.json()),
                fetch(`${BASE}/categories`).then(r => r.json()),
                fetch(`${BASE}/orders`).then(r => r.json()),
                fetch(`${BASE}/payments`).then(r => r.json()),
                fetch(`${BASE}/maintenances`).then(r => r.json()),
                fetch(`${BASE}/notifications`).then(r => r.json()),
                fetch(`${BASE}/suppliers`).then(r => r.json()),
                fetch(`${BASE}/admin-staff`).then(r => r.json())
            ]);

            const ordersArr = Array.isArray(orders) ? orders : [];
            const plantsArr = Array.isArray(plants) ? plants : [];
            const staffArr = Array.isArray(staffRes) ? staffRes : (staffRes?.data ?? []);

            // KPIs
            const totalPlants = plantsArr.length;
            const totalCategories = categories.length;
            const totalOrders = ordersArr.length;
            const totalSuppliers = suppliers.length;
            const totalStaff = staffArr.length;

            const uniqueUsers = new Set(ordersArr.map(o => o.user_id).filter(Boolean));
            const totalCustomers = uniqueUsers.size || 0;

            let totalRevenue = 0;
            if (payments && payments.length) {
                totalRevenue = payments.reduce((s, p) => s + parseFloat(p.amount || 0), 0);
            } else {
                totalRevenue = ordersArr.reduce((s, o) => s + parseFloat(o.total_amount || 0), 0);
            }

            const pendingOrdersCount = ordersArr.filter(o => o.status?.toLowerCase().includes('pending')).length;
            const lowStockItems = plantsArr.filter(p => (p.stock_quantity ?? 0) <= 10);
            const outOfStockItems = lowStockItems.filter(p => p.stock_quantity <= 0);
            const pendingMaint = maintenances.filter(m => m.status === 'pending' || m.status === 'in_progress').length;
            const notifCount = notifications.length;

            document.getElementById("statsGrid").innerHTML = `
                <div class="stat-card"><div class="icon">🌱</div><div class="label">Plants</div><div class="value">${totalPlants}</div></div>
                <div class="stat-card"><div class="icon">📦</div><div class="label">Orders</div><div class="value">${totalOrders}</div></div>
                <div class="stat-card"><div class="icon">💰</div><div class="label">Revenue</div><div class="value">$${totalRevenue.toFixed(2)}</div></div>
                <div class="stat-card"><div class="icon">👥</div><div class="label">Customers</div><div class="value">${totalCustomers}</div></div>
                <div class="stat-card"><div class="icon">📁</div><div class="label">Categories</div><div class="value">${totalCategories}</div></div>
                <div class="stat-card"><div class="icon">🚚</div><div class="label">Suppliers</div><div class="value">${totalSuppliers}</div></div>
                <div class="stat-card"><div class="icon">👤</div><div class="label">Staff</div><div class="value">${totalStaff}</div></div>
                <div class="stat-card"><div class="icon">⏳</div><div class="label">Pending Orders</div><div class="value">${pendingOrdersCount}</div></div>
                <div class="stat-card"><div class="icon">⚠️</div><div class="label">Low Stock</div><div class="value">${lowStockItems.length}</div></div>
                <div class="stat-card"><div class="icon">🔔</div><div class="label">Notifications</div><div class="value">${notifCount}</div></div>
            `;

            // Revenue chart (last 6 months)
            const now = new Date();
            const months = [];
            for (let i = 5; i >= 0; i--) {
                const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                months.push({
                    label: d.toLocaleString('default', { month: 'short' }),
                    key: `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}`,
                    total: 0
                });
            }
            ordersArr.forEach(o => {
                if (!o.order_date) return;
                const d = new Date(o.order_date);
                const k = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}`;
                const m = months.find(mm => mm.key === k);
                if (m) m.total += parseFloat(o.total_amount || 0);
            });
            const maxVal = Math.max(...months.map(m => m.total), 1);
            document.getElementById("revenueChart").innerHTML = months.map(m => {
                const h = (m.total / maxVal) * 100;
                return `<div class="bar-group">
                    <div class="bar" style="height:${h}%;" title="$${m.total.toFixed(0)}"></div>
                    <div class="bar-label">${m.label}</div>
                </div>`;
            }).join('');

            // Order status breakdown
            const statuses = {};
            ordersArr.forEach(o => {
                const s = (o.status || 'unknown').toLowerCase();
                statuses[s] = (statuses[s] || 0) + 1;
            });
            document.getElementById("orderStatusBreakdown").innerHTML = Object.entries(statuses).map(([s, cnt]) => {
                let cls = '';
                if (s.includes('pending')) cls = 'badge-pending';
                else if (s.includes('completed')) cls = 'badge-completed';
                else if (s.includes('cancelled')) cls = 'badge-cancelled';
                return `<span class="badge ${cls}" style="font-size:0.9rem; padding: 6px 14px;">${s}: ${cnt}</span>`;
            }).join('');

            // Low stock alerts
            document.getElementById("lowStockAlerts").innerHTML = lowStockItems.length ? lowStockItems.map(p => `
                <div class="alert-item ${p.stock_quantity <= 0 ? 'out' : ''}">
                    <span>${p.name}</span> <strong>${p.stock_quantity} left</strong>
                </div>`).join('') : '<p style="color:green;">All stock levels are fine ✅</p>';

            // Recent orders (last 5)
            const recent = ordersArr.slice(-5).reverse();
            document.getElementById("recentOrdersTable").innerHTML = recent.length ? recent.map(o => {
                let cls = 'badge';
                const s = (o.status || '').toLowerCase();
                if (s.includes('pending')) cls += ' badge-pending';
                else if (s.includes('completed')) cls += ' badge-completed';
                else if (s.includes('cancelled')) cls += ' badge-cancelled';
                return `<tr>
                    <td>#${o.order_id}</td>
                    <td>${o.order_date || '-'}</td>
                    <td>$${parseFloat(o.total_amount || 0).toFixed(2)}</td>
                    <td><span class="${cls}">${o.status || 'N/A'}</span></td>
                </tr>`;
            }).join('') : '<tr><td colspan="4">No orders yet</td></tr>';

            // Pending maintenance
            const pm = maintenances.filter(m => m.status === 'pending' || m.status === 'in_progress');
            document.getElementById("pendingMaintenance").innerHTML = pm.length ? pm.map(t => `
                <div class="alert-item" style="border-left-color:#2e7d32;">🔧 ${t.task_type} – Plant ID ${t.plant_id} <span>${t.scheduled_date||''}</span></div>
            `).join('') : '<p>No pending tasks 🎉</p>';

            // Notifications
            const lastNotifs = notifications.slice(-3).reverse();
            document.getElementById("notificationsList").innerHTML = lastNotifs.length ? lastNotifs.map(n => `
                <div class="alert-item" style="border-left-color:#6b8c42;">
                    <span>${n.message}</span> <span style="font-size:0.8rem;">${n.date || ''}</span>
                </div>`).join('') : '<p>No notifications</p>';

        } catch (err) {
            console.error(err);
            document.getElementById("statsGrid").innerHTML = '<p style="color:red;">Failed to load dashboard data.</p>';
        }
    }

    loadDashboard();
 