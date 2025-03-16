<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Lịch sử mua hàng</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($orderHistory)): ?>
        <p class="text-center">Bạn chưa có đơn hàng nào.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($orderHistory as $order): ?>
                <a href="/webbanhang/order/orderDetail?order_id=<?php echo $order->id; ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Đơn hàng #<?php echo $order->id; ?></h5>
                        <small><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></small>
                    </div>
                    <p class="mb-1">Tổng: <?php echo number_format($order->total_amount, 0, ',', '.'); ?> VNĐ</p>
                    <small>Người nhận: <?php echo htmlspecialchars($order->name); ?> - Địa chỉ: <?php echo htmlspecialchars($order->address); ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>