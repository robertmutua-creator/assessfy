<div style="max-width: 400px; margin: 4rem auto; padding: 0 1rem;">
    <div class="card-form">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.25rem; text-align: center; color: var(--black);">
            Sign in to <span style="color: var(--green);">Assessfy</span>
        </h2>

        <form action="/login" method="POST">
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="hidden" name="csrf_token" value="<?= isset($csrf_token) ? htmlspecialchars($csrf_token) : '' ?>">
            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>
    </div>
</div>