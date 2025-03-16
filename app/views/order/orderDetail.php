<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Chi tiết đơn hàng</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($orderDetails)): ?>
        <p class="text-center">Không tìm thấy chi tiết đơn hàng.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $detail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detail->name); ?></td>
                        <td><?php echo $detail->quantity; ?></td>
                        <td><?php echo number_format($detail->price, 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo number_format($detail->price * $detail->quantity, 0, ',', '.'); ?> VNĐ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="/webbanhang/order/orderHistory" class="btn btn-primary">Quay lại</a>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>