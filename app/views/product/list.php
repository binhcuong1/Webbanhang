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

    <!-- Khu vực quảng cáo -->
    <div class="ads-container mt-2 mb-4">
        <div class="row g-3">
            <!-- Banner 1:-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="ad-card">
                    <img src="/webbanhang/uploads/ads/daohongson.png" class="img-fluid" alt="Quảng cáo Creatine">
                    <div class="ad-overlay">
                        <h5>Creatine Nhai Nhựa Kéo</h5>
                        <p>Đẹp - Bổ Quần - Nhanh Phóng Củ Đáp</p>
                    </div>
                </div>
            </div>

            <!-- Banner 2:-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="ad-card">
                    <img src="/webbanhang/uploads/ads/d3k2.png" class="img-fluid" alt="Quảng cáo Vitamin">
                    <div class="ad-overlay">
                        <h5>D3-K2 hỗ trợ tiêu hóa Omega</h5>
                        <p>Nhiều hương vị mới</p>
                    </div>
                </div>
            </div>

            <!-- Banner 3:-->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="ad-card">
                    <img src="/webbanhang/uploads/ads/omega.png" class="img-fluid" alt="Quảng cáo Vitamin">
                    <div class="ad-overlay">
                        <h5>Omega hộp 60 Viên</h5>
                        <p>Mix Hỗn Tạp Bổ Mới Mẻ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiêu đề -->
    <!-- <h1 class="text-center mb-4 mt-3" style="color: #e63946; font-weight: bold;">
        Danh sách sản phẩm
    </h1> -->

    <!-- Lọc sản phẩm  -->
    <form method="GET" action="/webbanhang/Product" class="d-flex align-items-center mt-3 mb-3">
        <!-- Lọc theo loại -->
        <select name="category_id" id="category_id" class="form-select me-2" onchange="this.form.submit()">
            <option value="">
                <i class = "filter me-1"></i>Lọc theo loại
            </option>
            <?php
            require_once 'app/models/CategoryModel.php';
            $categoryModel = new CategoryModel((new Database())->getConnection());
            $categories = $categoryModel->getCategories();
            foreach ($categories as $category) {
                $selected = isset($_GET['category_id']) && $_GET['category_id'] == $category->id ? 'selected' : '';
                echo "<option value='{$category->id}' {$selected}>{$category->name}</option>";
            }
            ?>
        </select>

        <!-- Sắp xếp theo giá -->
        <select name="sort" id="sort" class="form-select me-2" onchange="this.form.submit()">
            <option value="">
                Sắp xếp giá
            </option>
            <option value="asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : ''; ?>>
                ⬆ Giá thấp đến cao
            </option>
            <option value="desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : ''; ?>>
                ⬇ Giá cao đến thấp
            </option>
        </select>

        <!-- Giữ lại tham số page nếu có -->
        <?php if (isset($_GET['page'])): ?>
            <input type="hidden" name="page" value="<?php echo htmlspecialchars($_GET['page'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
    </form>

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
                            <?php echo htmlspecialchars(number_format($product->price, 0, ',', '.'),
                                 ENT_QUOTES, 'UTF-8'); ?> VNĐ
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
                        
                        <!-- Chỉ hiển thị nút Sửa và Xóa nếu là admin -->
                        <?php if (SessionHelper::isAdmin()): ?>
                            <div class="mb-2 mb-sm-0">
                                <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            </div>
                        <?php endif; ?>

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