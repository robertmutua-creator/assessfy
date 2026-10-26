<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Assessfy') ?></title>
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    <!-- Top Sticky Header -->
    <header>
        <div class="brand-area">
            <button class="hamburger" id="hamburger-btn" aria-label="Toggle navigation">&#9776;</button>
            <div class="app-title">Assessfy</div>
        </div>
        <div class="profile-menu">
            <button class="profile-btn" id="profile-btn" aria-label="Profile menu">
                <img src="/images/avatar.png" alt="Profile" class="profile-img" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'36\' height=\'36\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2316a34a\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>';">
            </button>
            <div class="dropdown-content" id="profile-dropdown">
                <?php if (!empty($auth_user)): ?>
                    <a href="/logout">Logout</a>
                <?php else: ?>
                    <a href="/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div style="padding: 0.75rem 1.5rem; background: #f0fdf4; border-bottom: 1px solid #bbf7d0; color: #166534; font-size: 0.9rem;">
            <?php foreach ($_SESSION['flash'] as $message): ?>
                <div><?= htmlspecialchars($message) ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <!-- Main App Layout Wrapper -->
    <div class="app-layout">
        <!-- Sidebar -->
        <aside id="app-sidebar">
            <?php if (!empty($sidebar_links)): ?>
                <?php foreach ($sidebar_links as $label => $url): ?>
                    <a href="<?= $url ?>" class="<?= (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $url) ? 'active' : '' ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </aside>

        <!-- Main Content Area -->
        <main>
            <?php if (isset($viewPath)): ?>
                <?php require $viewPath; ?>
            <?php endif; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?= date('Y') ?> Assessfy. All rights reserved.
    </footer>

    <script src="/js/app.js"></script>
</body>

</html>