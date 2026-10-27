<section>
    <h1>Manage School Admins</h1>

    <?php if (empty($schools)): ?>
        <p>Create a school before registering a school admin.</p>
    <?php endif; ?>

    <form class="card-form" action="/admin/users/save" method="post">
        <h2><?= !empty($school_admin) ? 'Edit School Admin' : 'Register School Admin' ?></h2>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <?php if (!empty($school_admin)): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $school_admin['id'], ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="school_id">School</label>
                <select id="school_id" name="school_id" required <?= empty($schools) ? 'disabled' : '' ?>>
                    <option value="">Select a school</option>
                    <?php foreach ($schools as $school): ?>
                        <option value="<?= htmlspecialchars((string) $school['id'], ENT_QUOTES, 'UTF-8') ?>"
                            <?= (string) ($school_admin['school_id'] ?? '') === (string) $school['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($school['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" maxlength="100" required
                    value="<?= htmlspecialchars($school_admin['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label for="password">
                    Password <?= !empty($school_admin) ? '(leave blank to keep current password)' : '(minimum 8 characters)' ?>
                </label>
                <input id="password" name="password" type="password" minlength="8" <?= empty($school_admin) ? 'required' : '' ?>
                    autocomplete="new-password">
            </div>
            <button class="btn" type="submit" <?= empty($schools) ? 'disabled' : '' ?>>
                <?= !empty($school_admin) ? 'Update School Admin' : 'Register School Admin' ?>
            </button>
            <?php if (!empty($school_admin)): ?>
                <a class="btn" href="/admin/users">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Registered School Admins</h2>
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>School</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4">No school admins found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['school_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="actions">
                                    <a class="action action-edit" href="/admin/users/edit/<?= rawurlencode((string) $user['id']) ?>">Edit</a>
                                    <form action="/admin/users/delete/<?= rawurlencode((string) $user['id']) ?>" method="post" onsubmit="return confirmDelete('Are you sure you want to delete this school admin?')">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                        <button class="action action-delete" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>