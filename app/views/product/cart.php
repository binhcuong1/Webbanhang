<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-5" style="color:rgb(21, 21, 21); font-weight: bold;">
        Giỏ hàng
    </h1>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type'] == 'success' ? 'success' : 'warning'; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Danh sách giỏ hàng -->
    <?php if (!empty($cart)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/webbanhang/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="Product Image" style="max-width: 80px; border-radius: 5px;">
                                <?php else: ?>
                                    <span>Không có hình</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</td>
                            <td>
                                <form method="POST" action="/webbanhang/Cart/updateCart" class="d-flex align-items-center">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary me-2">-</button>
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" 
                                           class="form-control form-control-sm text-center" style="width: 60px;" min="1" readonly>
                                    <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary ms-2">+</button>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</td>
                            <td>
                                <a href="/webbanhang/Cart/removeFromCart/<?php echo $id; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tổng giá trị giỏ hàng -->
        <?php 
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        ?>
        <div class="text-end mt-4">
            <h4>Tổng cộng: <span class="text-danger"><?php echo htmlspecialchars(number_format($subtotal, 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</span></h4>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <p>Giỏ hàng của bạn đang trống.</p>
        </div>
    <?php endif; ?>

    <!-- Nút điều hướng -->
    <div class="d-flex justify-content-between mt-4">
        <a href="/webbanhang/Product" class="btn btn-secondary">Quay lại</a>
        <?php if (!empty($cart)): ?>
            <a href="/webbanhang/Order/checkout" class="btn btn-primary">Thanh toán</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>