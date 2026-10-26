<div class="landing-hero">
    <h1 class="landing-title">
        Welcome to <span class="text-green">Assessfy</span>
    </h1>
    <p class="landing-subtitle">
        A lightweight, secure, and lightning-fast platform designed for streamlined assessments, robust role management, and clean tracking.
    </p>
    <div class="landing-actions">
        <?php if (!empty($auth_user)): ?>
            <a href="/dashboard" class="btn">Go to Dashboard</a>
        <?php else: ?>
            <a href="/login" class="btn">Login to Account</a>
        <?php endif; ?>
    </div>
</div>