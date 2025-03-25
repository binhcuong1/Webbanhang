<!DOCTYPE html>
<html lang="vi">
<head>
    <?php require_once 'app/helpers/SessionHelper.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Whey Store - Cửa hàng thực phẩm bổ sung chất lượng cao">
    <meta name="keywords" content="whey protein, thực phẩm bổ sung, fitness, gym, dinh dưỡng thể thao">
    <title>Whey Store - Thực phẩm bổ sung chất lượng cao</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/webbanhang/uploads/favicon.ico" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Điều chỉnh kích thước chữ của nút Lọc và Xóa */
        .filter-container .btn {
            font-size: 0.8rem; /* Kích thước chữ, bạn có thể thay đổi giá trị này */
        }

        /* Nếu muốn áp dụng riêng cho từng nút */
        .filter-container .btn-primary {
            font-size: 0.8rem; /* Kích thước chữ cho nút Lọc */
        }

        .filter-container .btn-outline-secondary {
            font-size: 0.8rem; /* Kích thước chữ cho nút Xóa */
        }
        
        /* Thu nhỏ và căn trái bộ lọc */
        .filter-container {
            max-width: 600px; /* Giới hạn chiều rộng của bộ lọc */
            margin-left: 0; /* Căn trái */
            margin-right: auto; /* Đảm bảo không căn phải */
        }

        .card-header h5 {
            font-size: 0.8rem; /* Thu nhỏ tiêu đề */
        }

        .card-body {
            padding: 1rem; /* Giảm padding để form nhỏ gọn hơn */
        }

        .form-label {
            font-size: 0.8rem; /* Thu nhỏ chữ label */
        }

        .input-group-sm .form-select,
        .input-group-sm .input-group-text {
            font-size: 0.8rem; /* Thu nhỏ chữ trong select và input-group */
        }

        /* Contact Button */
        .contact-button {
            position: fixed;
            bottom: 80px; /* xích nút liên hệ lên 1 xíu  */
            right: 15px;
            z-index: 1000;
        }
        
        .contact-toggle {
            width: 80px; /* Tăng kích thước nút (từ 60px lên 80px) */
            height: 80px;
            display: flex;
            flex-direction: column; /* Đặt hướng cột để biểu tượng và chữ xếp dọc */
            align-items: center;
            justify-content: center;
            font-size: 16px; /* Giảm kích thước chữ để vừa với nút */
            line-height: 1.2; /* Điều chỉnh khoảng cách dòng để chữ không bị cắt */
            animation: blink 1.5s infinite;
            padding: 5px;
            text-align: center;
        }
        
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        /* Contact Popup */
        .contact-popup {
            position: fixed;
            bottom: 110px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            padding: 15px;
        }
        
        .contact-popup-header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .contact-popup-header h5 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .contact-popup-header p {
            font-size: 14px;
            margin: 0;
        }
        
        .contact-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .contact-option {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .contact-option:hover {
            background: #e9ecef;
        }
        
        .contact-option i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .contact-option i.fa-facebook-messenger {
            color: #0084ff;
        }
        
        .contact-option i.fa-comment {
            color: #0084ff;
        }
        
        .contact-option i.fa-phone-alt {
            color: #28a745;
        }
        
        .contact-close {
            position: absolute;
            bottom: -50px;
            right: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Product Detail */
        .card-img-top {
            transition: var(--transition);
        }
        
        .card-img-top:hover {
            transform: scale(1.05);
        }
        
        .card-title {
            font-size: 28px;
            font-weight: 700;
        }
        
        .card-text {
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: var(--dark-color);
        }
        
        /* Related Products */
        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-color);
        }

        :root {
            --primary-color: #e63946;
            --primary-dark: #d81e3e;
            --primary-light: #f8d7da;
            --secondary-color: #1d3557;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Top bar */
        .top-bar {
            background-color: var(--secondary-color);
            color: white;
            font-size: 14px;
            padding: 8px 0;
        }

        .top-bar a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .top-bar a:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .social-icons i {
            margin-left: 12px;
            font-size: 16px;
            transition: var(--transition);
        }

        .social-icons i:hover {
            transform: translateY(-2px);
        }

        /* Main Navbar */
        .main-navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 65px;
            transition: var(--transition);
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Search Form */
        .search-form {
            position: relative;
            flex-grow: 1;
            max-width: 500px;
            margin: 0 20px;
        }

        .search-form .form-control {
            padding: 12px 45px 12px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: var(--transition);
        }

        .search-form .form-control:focus {
            border-color: var(--secondary-color);
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(29, 53, 87, 0.25);
        }

        .search-form .form-control::placeholder {
            color: #6c757d;
            opacity: 0.8;
        }

        .search-form .search-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 18px;
            pointer-events: none;
        }

        /* Navigation */
        .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .navbar-nav .active > .nav-link,
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            padding: 10px 20px;
            color: var(--dark-color);
            font-weight: 400;
            transition: var(--transition);
        }

        .dropdown-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .dropdown-item:hover, 
        .dropdown-item:focus {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        /* Category Dropdown */
        .category-dropdown {
            min-width: 250px;
        }

        .category-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            font-size: 15px;
        }

        .category-dropdown .dropdown-item i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .category-dropdown .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        /* Cart Dropdown */
        .cart-icon {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: #fff;
            color: var(--primary-color);
            font-size: 12px;
            font-weight: bold;
            height: 20px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .cart-dropdown {
            width: 300px;
            padding: 15px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-details {
            flex-grow: 1;
            padding: 0 10px;
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 500;
        }

        .cart-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .cart-buttons .btn {
            flex: 1;
            padding: 8px 0;
            font-size: 14px;
        }

        /* Navbar Toggler */
        .navbar-toggler {
            border: none;
            padding: 8px;
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        /* User menu */
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-light {
            color: white;
            border-color: white;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Categories Menu (dòng dưới) */
        .categories-menu {
            background-color: var(--secondary-color); /* Màu nền giống top bar */
            padding: 10px 0;
        }

        .categories-nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .category-item {
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            text-decoration: none; /* Bỏ gạch chân */
        }

        .category-item:hover,
        .category-item:focus {
            background-color: rgba(255, 255, 255, 0.1); /* Hiệu ứng hover giống navbar */
            color: white;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .search-form {
                margin: 15px 0;
                max-width: 100%;
            }
            
            .navbar-nav {
                margin-top: 10px;
            }

            .categories-nav {
                flex-direction: column;
                align-items: center;
            }

            .category-item {
                width: 100%;
                text-align: center;
                padding: 12px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
        }

        @media (max-width: 576px) {
            .top-bar {
                text-align: center;
            }
            
            .top-bar .social-icons {
                margin-top: 8px;
            }
            
            .search-form .form-control {
                font-size: 14px;
                padding: 10px 40px 10px 15px;
            }
            
            .navbar-brand img {
                height: 50px;
            }

            .category-item {
                font-size: 16px; /* Đảm bảo dễ đọc trên màn hình nhỏ */
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span><i class="fas fa-phone-alt me-2"></i> Hotline: 0123-456-789</span>
                    <span class="ms-3"><i class="fas fa-envelope me-2"></i> Email: contact@wheystore.com</span>
                </div>
                <div class="col-md-6 text-end">
                    <span class="social-icons">
                        <a href="https://www.facebook.com/wheystore.official" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/@wheystorechannel6401" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@wheystore.vn?fbclid=IwY2xjawJI55RleHRuA2FlbQIxMAABHV6hpv14A39jgSgv79eW8VvyFIVp4hhsoVMVJZJGSCj3nuQ-TsFBD4Wb-w_aem_No2xiZVbtPIEEjqPKD3bcQ" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/webbanhang/product/">
                <img src="/webbanhang/uploads/logo-wheystore-1_1695035517.png" alt="Whey Store Logo">
            </a>

            <!-- Navbar Toggler Button -->
            <!-- Xử lý khi thu nhỏ màn hình  -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Form tìm kiếm -->
                <form method="GET" action="/webbanhang/Product/search" class="search-form mx-lg-auto">
                    <input type="text" name="keyword" class="form-control" 
                           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                           placeholder="Tìm kiếm sản phẩm..." 
                           aria-label="Tìm kiếm sản phẩm">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </form>

                <!-- Main Navigation -->
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Trang chủ -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/webbanhang/product/') ? 'active' : ''; ?>" href="/webbanhang/product/">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>

                    <!-- Sản phẩm -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/collection/all">
                            <i class="fas fa-box-open me-1"></i> Sản phẩm
                        </a>
                    </li> -->

                    <!-- Admin Section -->
                    <!-- <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cogs me-1"></i> Quản lý
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="/webbanhang/ lýlýProduct/"><i class="fas fa-list"></i> Danh sách sản phẩm</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/Category/list"><i class="fas fa-tags"></i> Danh sách danh mục</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/User/manageRoles"><i class="fas fa-user-shield"></i> 
                                    Quản lý phân quyền</a>
                                </li>
                                <li><a class="dropdown-item" href="/webbanhang/Order/list"><i class="fas fa-shopping-bag"></i> 
                                    Quản lý đơn hàng</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/webbanhang/Product/add"><i class="fas fa-plus-circle"></i> Thêm sản phẩm</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/Category/add"><i class="fas fa-folder-plus"></i> Thêm danh mục</a></li>
                            </ul>
                        </li>
                    <?php endif; ?> -->

                    <!-- User Account -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <span class = "fas fa-user-circle">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </span>
                            <?php else: ?>
                                <i class="fas fa-user me-1"></i> Tài khoản
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <li>
                                    <a class="dropdown-item" href="/webbanhang/account/profile"><i class="fas fa-user-circle"></i> 
                                        Thông tin tài khoản
                                    </a>
                                </li>
                                
                                <?php if (SessionHelper::isUser()): ?>
                                    <li><a class="dropdown-item" href="/webbanhang/order/orderHistory"><i class="fas fa-history"></i> Lịch sử đơn hàng</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/webbanhang/account/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/webbanhang/account/login"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/account/register"><i class="fas fa-user-plus"></i> Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- Shopping Cart -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-badge">
                                    <?php 
                                        $cart = $_SESSION['cart'] ?? [];
                                        echo count($cart);
                                    ?>
                                </span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end cart-dropdown" aria-labelledby="cartDropdown">
                            <?php if (empty($cart)): ?>
                                <div class="text-center py-3">
                                    <i class="fas fa-shopping-basket fa-2x mb-3 text-muted"></i>
                                    <p>Giỏ hàng của bạn đang trống</p>
                                </div>
                            <?php else: ?>
                                <h6 class="dropdown-header">Giỏ hàng của bạn</h6>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <?php foreach ($cart as $id => $item): ?>
                                        <div class="cart-item">
                                            <div class="cart-item-details">
                                                <div class="fw-bold text-truncate" style="max-width: 180px;" title="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                                </div>
                                                <small><?php echo $item['quantity']; ?> x <span class="cart-item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</span></small>
                                            </div>
                                            <a href="/webbanhang/Cart/removeFromCartList/<?php echo $id; ?>" class="text-danger" title="Xóa"><i class="fas fa-times"></i></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="d-flex justify-content-between align-items-center my-2">
                                    <span class="fw-bold">Tổng tiền:</span>
                                    <span class="fw-bold text-danger">
                                        <?php
                                            $total = 0;
                                            foreach ($cart as $item) {
                                                $total += $item['price'] * $item['quantity'];
                                            }
                                            echo number_format($total, 0, ',', '.') . ' ₫';
                                        ?>
                                    </span>
                                </div>
                                <div class="cart-buttons">
                                    <a href="/webbanhang/Cart/cart" class="btn btn-outline-secondary">Xem giỏ hàng</a>
                                    <a href="/webbanhang/Order/checkout" class="btn btn-primary">Thanh toán</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Categories Menu (dòng dưới) -->
    <!-- <div class="categories-menu">
        <div class="container">
            <div class="categories-nav">
                <div class="dropdown">
                    <a class="category-item dropdown-toggle" href="#" id="categoryDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-list me-1"></i> Danh mục
                    </a>
                    <ul class="dropdown-menu category-dropdown" aria-labelledby="categoryDropdown">
                        <?php
                        require_once 'app/models/CategoryModel.php';
                        $categoryModel = new CategoryModel((new Database())->getConnection());
                        $categories = $categoryModel->getCategories();
                        foreach ($categories as $category):
                        ?>
                            <li>
                                <a class="dropdown-item" href="/webbanhang/Product?category_id=<?php echo $category->id; ?>">
                                    <i class="fas fa-tag me-2"></i>
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>