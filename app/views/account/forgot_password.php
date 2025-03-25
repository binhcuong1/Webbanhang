<?php include 'app/views/shares/header.php'; ?>

<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); 
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
                unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php endif; ?>

<div class="container mt-5">
    <h1 class="text-center mb-5" style="color:rgb(21, 21, 21); font-weight: bold;">
        Quên mật khẩu
    </h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <form action="/webbanhang/account/checkUsername" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Tên người dùng</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <button type="submit" class="btn btn-primary">Gửi</button>
        <a href="/webbanhang/account/login" class="btn btn-secondary">Quay lại đăng nhập</a>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>