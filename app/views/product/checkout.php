<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Thanh toán</h1>

    <!-- Danh sách giỏ hàng -->
    <?php if (!empty($cart)): ?>
        <div class="table-responsive mb-4">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</td>
                            <td><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</td>
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
        <div class="text-end mb-4">
            <h4>Tổng cộng: <span class="text-danger"><?php echo htmlspecialchars(number_format($subtotal, 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ</span></h4>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mb-4">
            <p>Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán!</p>
        </div>
    <?php endif; ?>

    <!-- Form thông tin thanh toán -->
    <form method="POST" action="/webbanhang/Order/processCheckout">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Họ tên:</label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ tên..." required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="phone" class="col-sm-2 col-form-label">Số điện thoại:</label>
            <div class="col-sm-10">
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại..." required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="address" class="col-sm-2 col-form-label">Địa chỉ:</label>
            <div class="col-sm-10">
                <textarea id="address" name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ..." required></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/webbanhang/Cart/cart" class="btn btn-secondary">Quay lại</a>
            <?php if (!empty($cart)): ?>
                <button type="submit" class="btn btn-custom" onclick="return confirm('Bạn có chắc chắn muốn thanh toán?');">Thanh toán</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>