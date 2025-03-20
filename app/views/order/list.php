<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1 class="text-center mb-5" style="color:rgb(21, 21, 21); font-weight: bold;">
        Quản lý đơn hàng
    </h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <!-- Form lọc đơn hàng -->
    <form method="GET" action="/webbanhang/Order/list" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="phone" class="form-label">
                    Số điện thoại
                </label>
                <input type="text" name="phone" id="phone" class="form-control" 
                       value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                       placeholder="Nhập số điện thoại">
            </div>
            <div class="col-md-4">
                <label for="date" class="form-label">Ngày mua hàng</label>
                <input type="date" name="date" id="date" class="form-control" 
                       value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    Lọc
                </button>
                <a href="/webbanhang/Order/list" class="btn btn-secondary">
                    Xóa bộ lọc
                </a>
            </div>
        </div>
    </form>
    <!-- Form lọc đơn hàng -->

    <?php if (empty($orders)): ?>
        <p class="text-center">
            Chưa có đơn hàng nào.
        </p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($orders as $order): ?>
                <a href="/webbanhang/Order/adminOrderDetail?order_id=<?php echo $order->id; ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">
                            Đơn hàng #<?php echo $order->id; ?> (Khách hàng: <?php echo htmlspecialchars($order->username); ?>)
                        </h5>
                        <small><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></small>
                    </div>
                    <p class="mb-1">Tổng: <?php echo number_format($order->total_amount, 0, ',', '.'); ?> VNĐ</p>
                    <small>Người nhận: <?php echo htmlspecialchars($order->name); ?> - Địa chỉ: <?php echo htmlspecialchars($order->address); ?> - SĐT: <?php echo htmlspecialchars($order->phone); ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>