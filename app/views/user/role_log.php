<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h2 class="my-4">Lịch sử thay đổi vai trò</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Admin thực hiện</th>
                <th>Người dùng</th>
                <th>Vai trò cũ</th>
                <th>Vai trò mới</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log->id; ?></td>
                    <td><?php echo htmlspecialchars($log->admin_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($log->user_name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($log->old_role, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($log->new_role, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $log->created_at; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/webbanhang/User/manageRoles" class="btn btn-secondary mt-3">Quay lại quản lý vai trò</a>
</div>
<?php include 'app/views/shares/footer.php'; ?>