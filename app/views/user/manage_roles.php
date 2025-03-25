<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <!-- Hiển thị thông báo -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 class="my-4">Quản lý vai trò người dùng</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên người dùng</th>
                <th>Vai trò hiện tại</th>
                <th>Thay đổi vai trò</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->id; ?></td>
                    <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->role, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <form method="POST" action="/webbanhang/User/updateRole/<?php echo $user->id; ?>">
                            <div class="input-group">
                                <select name="new_role" class="form-select">
                                    <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="customer" <?php echo $user->role === 'customer' ? 'selected' : ''; ?>>Khách hàng</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/webbanhang/User/roleLog" class="btn btn-secondary mt-3">Xem lịch sử thay đổi vai trò</a>
</div>
<?php include 'app/views/shares/footer.php'; ?>