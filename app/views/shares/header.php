<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'app/helpers/SessionHelper.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* Tùy chỉnh badge giỏ hàng */
        .navbar-nav .badge {
            position: relative;
            top: -5px;
            font-size: 12px;
        }
        .navbar-nav .nav-link:hover .fa-shopping-cart {
            color: #fff;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 1px solid #e63946;
        }
        .btn-custom {
            background-color: #e63946;
            background-image: linear-gradient(135deg, #e63946, #d81e3e);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-image: linear-gradient(135deg, #d81e3e, #e63946);
            color: white;
        }
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
        .search-form {
            position: relative;
            flex-grow: 1;
            max-width: 500px;
            margin: 0 20px;
        }
        .search-form .form-control {
            padding-right: 40px;
            border-radius: 50px;
            border: 1px solid #ced4da;
            background-color: #fff;
        }
        .search-form .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }
        .search-form .form-control::placeholder {
            font-size: 20px;
            color: #6c757d;
        }
        .navbar-dark {
            background-color: #e63946;
            background-image: linear-gradient(135deg, #e63946, #d81e3e);
        }
        /* Tùy chỉnh dropdown */
        .dropdown-menu {
            background-color: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 150px;
        }
        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
        }
        .dropdown-item:hover {
            background-color: #e63946;
            color: #fff;
            border-radius: 4px;
        }
        .nav-link i {
            margin-right: 5px;
        }
        /* Tùy chỉnh vị trí của giỏ hàng */
        .navbar-nav .nav-item:last-child {
            margin-right: -55px; /* Xích sang phải 20px */
            margin-top: 50px; /* Xích xuống dưới 5px */
        }
        @media (max-width: 576px) {
            .dropdown-menu {
                min-width: 120px;
                font-size: 13px;
            }
            .navbar-nav .nav-item:last-child {
                margin-right: 10px; /* Giảm khoảng cách trên mobile */
                margin-top: 3px; /* Giảm khoảng cách trên mobile */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-lg pt-1">
            <!-- Tạo logo -->
            <a class="navbar-brand" href="/webbanhang/product/"> 
                <img src="/webbanhang/uploads/logo-wheystore-1_1695035517.png" alt="Logo quản lý sản phẩm" 
                     class="me-0" style="height: 65px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Form tìm kiếm -->
                <form method="GET" action="/webbanhang/Product/search" class="search-form d-flex">
                    <input type="text" name="keyword" class="form-control" 
                           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                           placeholder="Tìm Sản Phẩm,..." 
                           aria-label="Tìm kiếm sản phẩm">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </form>

                <!-- Tạo danh sách menu đẩy sang bên phải -->
                <ul class="navbar-nav">
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item">   
                            <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                        </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Category/list">Danh sách danh mục</a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Chỉ hiển thị dropdown "Thêm mới" nếu là admin -->
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-plus me-1"></i> Thêm mới
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/webbanhang/Product/add">Thêm sản phẩm</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/Category/add">Thêm danh mục</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Phần kiểm tra đăng nhập/đăng xuất -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            <?php else: ?>
                                Tài khoản
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <li><a class="dropdown-item" href="/webbanhang/account/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/account/logout">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/webbanhang/account/login">Login</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/account/register">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- Thêm biểu tượng giỏ hàng -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-light text-dark rounded-pill ms-1">
                                <?php 
                                    $cart = $_SESSION['cart'] ?? [];
                                    echo count($cart);
                                ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="cartDropdown" style="min-width: 250px;">
                            <?php if (empty($cart)): ?>
                                <li class="dropdown-item text-center">Giỏ hàng trống</li>
                            <?php else: ?>
                                <?php foreach ($cart as $id => $item): ?>
                                    <li class="dropdown-item">
                                        <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> 
                                        (x<?php echo $item['quantity']; ?>) - 
                                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ
                                    </li>
                                <?php endforeach; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-item">
                                    <a href="/webbanhang/Cart/cart" class="btn btn-primary btn-sm w-100">Xem giỏ hàng</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>