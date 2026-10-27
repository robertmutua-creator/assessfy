<div class="dashboard-header">
    <h1 class="page-title">System Dashboard</h1>
    <p class="page-subtitle">Overview of system statistics and core metrics.</p>
</div>

<!-- Summary Cards Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Schools</h3>
        <div class="stat-value"><?= htmlspecialchars($total_schools ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="stat-value"><?= htmlspecialchars($total_users ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <h3>Active Admins</h3>
        <div class="stat-value"><?= htmlspecialchars($total_admins ?? 0) ?></div>
    </div>
    <div class="stat-card">
        <h3>System Status</h3>
        <div class="stat-value stat-value-status">
            <span class="status-dot"></span>
            Operational
        </div>
    </div>
</div>

<!-- Quick Management Actions -->
<div class="card-form">
    <h2 class="card-form-title">Quick Actions</h2>
    <div class="dashboard-actions">
        <a href="/admin/schools" class="btn">Manage Schools</a>
        <a href="/admin/users" class="btn btn-dark">Manage Users</a>
    </div>
</div>