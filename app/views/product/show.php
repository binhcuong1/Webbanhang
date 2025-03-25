<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
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

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/webbanhang/product/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/webbanhang/Product">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></li>
        </ol>
    </nav>

    <!-- Chi tiết sản phẩm -->
    <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                     style="height: 400px; object-fit: contain;">
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h1 class="card-title text-primary"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="card-text text-muted">
                        <strong>Danh mục:</strong> <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="card-text text-danger fs-4">
                        <strong>Giá:</strong> <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                    </p>
                    <p class="card-text">
                        <strong>Mô tả:</strong> 
                        <?php echo htmlspecialchars($product->description ?? 'Không có mô tả', ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between flex-column flex-sm-row">
                    <!-- Nút Thêm vào giỏ hàng -->
                    <form method="POST" action="/webbanhang/Cart/addToCart/<?php echo $product->id; ?>" class="m-0">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    </form>

                    <!-- Nút Sửa và Xóa (chỉ hiển thị nếu là admin) -->
                    <?php if (SessionHelper::isAdmin()): ?>
                        <div class="mt-2 mt-sm-0">
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-lg me-2">Sửa</a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                               class="btn btn-danger btn-lg" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan (tùy chọn) -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-5">
            <h2 class="section-title mb-4">Sản phẩm liên quan</h2>
            <div class="row row-cols-1 row-cols-md-4 g-3">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="col">
                        <div class="card h-100 shadow-lg">
                            <a href="/webbanhang/Product/show/<?php echo $relatedProduct->id; ?>">
                                <img src="/webbanhang/<?php echo htmlspecialchars($relatedProduct->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($relatedProduct->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                     style="height: 200px; object-fit: contain;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="/webbanhang/Product/show/<?php echo $relatedProduct->id; ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($relatedProduct->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-danger">
                                        <strong>Giá:</strong> <?php echo number_format($relatedProduct->price, 0, ',', '.'); ?> VNĐ
                                    </p>
                                </div>
                            </a>    
                            <div class="card-footer d-flex justify-content-between">
                                <form method="POST" action="/webbanhang/Cart/addToCart/<?php echo $relatedProduct->id; ?>" class="m-0">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-cart-plus me-1"></i> Thêm
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>