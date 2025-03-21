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

    <!-- Khu vực quảng cáo cải tiến với carousel -->
    <div class="mt-3 mb-4">
        <div id="adsCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Banner 1 -->
                <div class="carousel-item active">
                    <div class="ads-card position-relative">
                        <a href="/webbanhang/Product/show/<?php echo 23 ?>">
                            <img src="/webbanhang/uploads/ads/daohongson.png" class="d-block w-100 rounded" alt="Quảng cáo Creatine">
                        </a>
                            <div class="ads-overlay position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h5 class="mb-1">Creatine Nhai Nhựa Kéo</h5>
                            <p class="mb-0">Đẹp - Bổ Quần - Nhanh Phóng Củ Đáp</p>
                        </div>
                    </div>
                </div>
                <!-- Banner 2 -->
                <div class="carousel-item">
                    <div class="ads-card position-relative">
                        <a href="/webbanhang/Product/show/<?php echo 24 ?>">
                            <img src="/webbanhang/uploads/ads/d3k2.png" class="d-block w-100 rounded" alt="Quảng cáo Vitamin">
                        </a>
                            <div class="ads-overlay position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h5 class="mb-1">D3-K2 hỗ trợ tiêu hóa Omega</h5>
                            <p class="mb-0">Nhiều hương vị mới</p>
                        </div>
                    </div>
                </div>
                <!-- Banner 3 -->
                <div class="carousel-item">
                    <div class="ads-card position-relative">
                        <a href="/webbanhang/Product/show/<?php echo 25 ?>">
                            <img src="/webbanhang/uploads/ads/omega.png" class="d-block w-100 rounded" alt="Quảng cáo Vitamin">
                        </a>
                            <div class="ads-overlay position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h5 class="mb-1">Omega hộp 60 Viên</h5>
                            <p class="mb-0">Mix Hỗn Tạp Bổ Mới Mẻ</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#adsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#adsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#adsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#adsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#adsCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>

    <!-- Form lọc sản phẩm -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-filter me-2"></i>Lọc sản phẩm
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="/webbanhang/Product" class="row g-3 align-items-end">
                <!-- Lọc theo loại -->
                <div class="col-md-4 position-relative">
                    <label for="category_id" class="form-label fw-medium">Loại sản phẩm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-tags text-muted"></i>
                        </span>
                        <select name="category_id" id="category_id" class="form-select border-start-0">
                            <option value="">Tất cả loại sản phẩm</option>
                            <?php
                            require_once 'app/models/CategoryModel.php';
                            $categoryModel = new CategoryModel((new Database())->getConnection());
                            $categories = $categoryModel->getCategories();
                            foreach ($categories as $category) {
                                $selected = isset($_GET['category_id']) && $_GET['category_id'] == $category->id ? 'selected' : '';
                                echo "<option value='{$category->id}' {$selected}>" . htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Sắp xếp theo giá -->
                <div class="col-md-4 position-relative">
                    <label for="sort" class="form-label fw-medium">Sắp xếp giá</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-sort-amount-down text-muted"></i>
                        </span>
                        <select name="sort" id="sort" class="form-select border-start-0">
                            <option value="">Mặc định</option>
                            <option value="asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : ''; ?>>
                                ⬆ Giá thấp đến cao
                            </option>
                            <option value="desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : ''; ?>>
                                ⬇ Giá cao đến thấp
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Nút lọc và xóa bộ lọc -->
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i>Lọc
                        </button>
                        <a href="/webbanhang/Product" class="btn btn-outline-secondary flex-fill">
                            <i class="fas fa-times me-2"></i>Xóa bộ lọc
                        </a>
                    </div>
                </div>

                <!-- Giữ lại tham số page nếu có -->
                <?php if (isset($_GET['page'])): ?>
                    <input type="hidden" name="page" value="<?php echo htmlspecialchars($_GET['page'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Hiển thị kết quả tìm kiếm -->
    <?php if (isset($_GET['keyword']) || isset($_GET['category_id']) || isset($_GET['sort'])): ?>
    <div class="alert alert-light shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-medium">Kết quả lọc:</span>
                <?php if(isset($_GET['keyword']) && !empty($_GET['keyword'])): ?>
                    <span class="badge bg-info text-dark ms-2">
                        <i class="fas fa-search me-1"></i>
                        <?php echo htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['category_id']) && !empty($_GET['category_id'])): 
                    $categoryName = "Không xác định";
                    foreach($categories as $category) {
                        if($category->id == $_GET['category_id']) {
                            $categoryName = $category->name;
                            break;
                        }
                    }
                ?>
                    <span class="badge bg-secondary ms-2">
                        <i class="fas fa-tag me-1"></i>
                        <?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                <?php endif; ?>
                
                <?php if(isset($_GET['sort']) && !empty($_GET['sort'])): ?>
                    <span class="badge bg-warning text-dark ms-2">
                        <i class="fas fa-sort-amount-<?php echo $_GET['sort'] == 'asc' ? 'up' : 'down'; ?> me-1"></i>
                        <?php echo $_GET['sort'] == 'asc' ? 'Giá tăng dần' : 'Giá giảm dần'; ?>
                    </span>
                <?php endif; ?>
            </div>
            <span class="text-muted">Tìm thấy <strong><?php echo count($products); ?></strong> sản phẩm</span>
        </div>
    </div>
    <?php endif; ?>

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
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                            <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                            class="card-img-top" alt="Hình ảnh sản phẩm" style="height: 200px; object-fit: contain;">
                        </a>
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
                       href="/webbanhang/Product/?page=<?php echo $i; ?><?php echo isset($_GET['category_id']) ? '&category_id=' . htmlspecialchars($_GET['category_id'], ENT_QUOTES, 'UTF-8') : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort'], ENT_QUOTES, 'UTF-8') : ''; ?><?php echo isset($_GET['keyword']) ? '&keyword=' . htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>
<?php include 'app/views/shares/footer.php'; ?>