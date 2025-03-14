<?php include 'app/views/shares/header.php'; ?>
<div class = "container">
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

    <!-- Tiêu đề -->
    <h1 class="text-center mb-4 mt-3" style="color: #e63946; font-weight: bold;">
        Danh sách sản phẩm
    </h1>

    <!-- row-cols-1: 1 cột trên màn hình nhỏ (mobile)
         row-cols-md-3: 3 cột trên màn hình từ trung bình trở lên
         g-4: Khoảng cách giữa các cột (gutter) là 1.5rem  -->
    <div class = "row row-cols-1 row-cols-md-4 g-3">
        <?php foreach($products as $product): ?>
            <div class = "col"> <!-- col: Mỗi sản phẩm nằm trong một cột  -->
                <!-- h-100: Đảm bảo các thẻ cao bằng nhau
                     shadow-sm: Thêm bóng nhẹ cho thẻ, tạo hiệu ứng nổi -->
                <div class = "card h-100 shadow-lg">
                    <!-- Hình ảnh sản phẩm -->
                    <?php if ($product->image): ?>
                        <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top" alt="Hình ảnh sản phẩm" style="height: 200px; object-fit: contain;">
                    <?php endif; ?>
                    
                    <!-- Chứa phần thân của thẻ  -->
                    <div class = "card-body">
                        <!-- Tên sản phẩm -->
                        <h5 class = "card-title">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <!-- Giá sản phẩm  -->
                        <p class="card-text">
                            <strong>
                                Giá:
                            </strong> 
                            <?php echo htmlspecialchars(number_format($product->price, 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VNĐ
                        </p>
                        <!-- Danh mục của sản phẩm  -->
                        <p class="card-text">
                            <strong>
                                Loại:
                            </strong> 
                            <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between flex-column flex-sm-row">
                        <div class="mb-2 mb-sm-0">
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                        </div>
                        <form method="POST" action="/webbanhang/Cart/addToCart/<?php echo $product->id; ?>" class="m-0">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-cart-plus me-1"></i> Thêm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Phân trang -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php
            $totalPages = isset($totalPages) ? $totalPages : 1;
            $page = isset($page) ? $page : 1;
            for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" 
                       href="/webbanhang/Product/?page=<?php echo $i; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>
<?php include 'app/views/shares/footer.php'; ?>