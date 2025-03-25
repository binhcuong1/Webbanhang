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
        Xác minh đơn hàng
    </h1>
    <p class="text-center">Vui lòng chọn ít nhất 2 đơn hàng thuộc về bạn.</p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <form action="/webbanhang/account/checkOrders" method="post">
        <?php if (!empty($orders)): ?>
            <div class="list-group">
                <?php foreach ($orders as $order): ?>
                    <div class="list-group-item">
                        <input type="checkbox" name="orders[]" value="<?php echo $order->id; ?>" id="order_<?php echo $order->id; ?>">
                        <label for="order_<?php echo $order->id; ?>">
                            Đơn hàng #<?php echo $order->id; ?> - 
                            Tổng: <?php echo number_format($order->total_amount, 0, ',', '.'); ?> VNĐ - 
                            Ngày: <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Không tìm thấy đơn hàng nào.</p>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary mt-3">Xác minh</button>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>