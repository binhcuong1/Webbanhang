<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Xác nhận đơn hàng</h1>

    <!-- Thông báo thành công -->
    <div class="card bg-success text-white text-center mb-4">
        <div class="card-body">
            <h3 class="card-title"><i class="bi bi-check-circle-fill me-2"></i> Đặt hàng thành công!</h3>
            <p class="card-text">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công.</p>
        </div>
    </div>

    <!-- Thông tin đơn hàng cơ bản -->
    <?php 
    // Đảm bảo $cart và $order luôn tồn tại
    $cart = $cart ?? [];
    $order = $order ?? [];
    ?>

    <?php if (!empty($cart) || !empty($order)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($order['id'] ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['name'] ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'] ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Ngày đặt hàng:</strong> <?php echo htmlspecialchars($order['created_at'] ?? date('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p>

                <?php if (!empty($cart)): ?>
                    <?php 
                    $subtotal = 0;
                    foreach ($cart as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                    }
                    ?>
                    <p><strong>Tổng cộng:</strong> <?php echo htmlspecialchars(number_format($subtotal, 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mb-4">
            <p>Không tìm thấy thông tin đơn hàng. Vui lòng kiểm tra lại!</p>
        </div>
    <?php endif; ?>

    <!-- Nút điều hướng -->
    <div class="text-center mt-4">
        <a href="/webbanhang/Product/" class="btn btn-secondary">Tiếp tục mua sắm</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>