<section>
    <h1>Manage Schools</h1>

    <form class="card-form" action="/admin/schools/save" method="post" enctype="multipart/form-data">
        <h2><?= !empty($school) ? 'Edit School' : 'Create School' ?></h2>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <?php if (!empty($school)): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $school['id'], ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="name">School name</label>
                <input id="name" name="name" type="text" maxlength="255" required
                    value="<?= htmlspecialchars($school['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input id="address" name="address" type="text" maxlength="255" required
                    value="<?= htmlspecialchars($school['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label for="logo">School logo (JPEG, PNG, GIF or WebP; max 5 MB)</label>
                <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/gif,image/webp">
                <?php if (!empty($school['logo_path'])): ?>
                    <span>Current logo: <a href="<?= htmlspecialchars($school['logo_path'], ENT_QUOTES, 'UTF-8') ?>">View</a></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" <?= ($school['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($school['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button class="btn" type="submit"><?= !empty($school) ? 'Update School' : 'Save School' ?></button>
            <?php if (!empty($school)): ?>
                <a class="btn" href="/admin/schools">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <h2>Current Schools</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Logo</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($schools)): ?>
                <tr>
                    <td colspan="6">No schools found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($schools as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($record['address'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if (!empty($record['logo_path'])): ?>
                                <a href="<?= htmlspecialchars($record['logo_path'], ENT_QUOTES, 'UTF-8') ?>">View logo</a>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(ucfirst($record['status']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($record['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <div class="actions">
                                <a class="action action-edit" href="/admin/schools/edit/<?= rawurlencode((string) $record['id']) ?>">Edit</a>
                                <form action="/admin/schools/delete/<?= rawurlencode((string) $record['id']) ?>" method="post" onsubmit="return confirmDelete('Are you sure you want to delete this school?')">
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
</section>