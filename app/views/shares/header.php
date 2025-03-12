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
            top: -5px; /* Nâng badge lên một chút để căn chỉnh với biểu tượng */
            font-size: 12px; /* Kích thước chữ nhỏ */
        }

        /* Khi hover vào biểu tượng giỏ hàng */
        .navbar-nav .nav-link:hover .fa-shopping-cart {
            color: #fff; /* Đổi màu biểu tượng khi hover */
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
            flex-grow: 1; /* Thanh tìm kiếm mở rộng để chiếm không gian */
            max-width: 500px; /* Giới hạn chiều rộng tối đa */
            margin: 0 20px; /* Khoảng cách hai bên */
        }

        .search-form .form-control {
            padding-right: 40px; /* Để chừa chỗ cho biểu tượng kính lúp */
            border-radius: 50px; /* Bo tròn ô nhập liệu */
            border: 1px solid #ced4da; /* Viền nhẹ */
            background-color: #fff; /* Màu nền trắng */
        }

        .search-form .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d; /* Màu biểu tượng */
            pointer-events: none; /* Không cho phép nhấp vào biểu tượng */
        }

        /* Tùy chỉnh kích thước chữ placeholder */
        .search-form .form-control::placeholder {
            font-size: 20px; /* Giảm kích thước chữ placeholder xuống 12px */
            color: #6c757d; /* Đảm bảo màu placeholder dễ nhìn */
        }

        /* Đổi màu nền navbar thành biến thể đẹp hơn của danger */
        .navbar-dark {
            background-color: #e63946; /* Tông đỏ coral, mềm mại và hiện đại hơn */
            /* Hoặc thử: #ff4d4d (đỏ tươi nhẹ) hoặc #c0392b (đỏ gạch ấm áp) */
            background-image: linear-gradient(135deg, #e63946, #d81e3e);
        }
    </style>
</head>
<body>
    <!-- Tạo thanh điều hướng với các thuộc tính cơ bản
         navbar-dark: Chủ đề tối, chữ trắng, phù hợp với nền tối
         bg-danger: Màu nền đỏ -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        
        <!-- Tạo vùng chứa cho nội dung navbar, chiếm toàn bộ chiều rộng
            pt-1: Tạo phần đệm trên cùng nhỏ -->
        <div class="container-lg pt-1">

            <!-- Tạo logo
                navbar-brand: Định dạng tên thương hiệu với kích thước và kiểu chữ nổi bật
                me-3: Tạo khoảng cách bên phải cho logo -->
            <a class="navbar-brand" href="/webbanhang/product/"> 
                <img src = "/webbanhang/uploads/logo-wheystore-1_1695035517.png"
                alt="Logo quản lý sản phẩm" class="me-0" style="height: 55px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Chứa phần menu có thể thu gọn/mở rộng
                collapse: làm phần tử ẩn đi ban đầu -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- Form tìm kiếm -->
                <form method="GET" action="/webbanhang/Product/search" class="search-form d-flex">
                    <input type="text" name="keyword" class="form-control" 
                           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>" 
                           placeholder="Tìm Sản Phẩm,..." 
                           aria-label="Tìm kiếm sản phẩm">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </form>

                <!-- Tạo danh sách menu đẩy sang bên phải
                    navbar-nav: Định dạng danh sách menu nằm ngang trên desktop
                    ms-auto: Đẩy danh sách sang bên phải (margin-start-auto) -->
                <ul class="navbar-nav">

                    <!-- nav-item: Định dạng mỗi mục trong danh sách menu, đảm bảo khoảng cách và căn chỉnh -->
                    <li class="nav-item">   
                        <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category/list">Danh sách danh mục</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/destroy">Destroy</a>
                    </li> -->
                    <!-- Tạo mục menu chứa dropdown
                        dropdown: Biến mục này thành dropdown menu -->
                    <li class="nav-item dropdown">

                        <!-- Tạo nút toggle cho dropdown
                            nav-link: Định dạng như liên kết thông thường
                            dropdown-toggle: Thêm biểu tượng mũi tên xuống và chức năng toggle
                            data-bs-toggle="dropdown": Kích hoạt dropdown -->
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-plus me-1"></i> Thêm mới
                        </a>

                        <!-- Tạo danh sách thả xuống của dropdown
                            aria-labelledby="navbarDropdown": Liên kết với ID của nút toggle, cải thiện truy cập -->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- Tạo mục trong dropdown -->
                            <li><a class="dropdown-item" href="/webbanhang/Product/add">Thêm sản phẩm</a></li>
                            <li><a class="dropdown-item" href="/webbanhang/Category/add">Thêm danh mục</a></li>
                        </ul>

                        <!-- Phần kiểm tra đăng nhập/đăng xuất -->
                        <li class="nav-item">
                            <?php if (SessionHelper::isLoggedIn()) { ?>
                                <a class="nav-link"><?php echo $_SESSION['username']; ?></a>
                            <?php } else { ?>
                                <a class="nav-link" href="/webbanhang/account/login">Login</a>
                            <?php } ?>
                        </li>
                        <li class="nav-item">
                            <?php if (SessionHelper::isLoggedIn()) { ?>
                                <a class="nav-link" href="/webbanhang/account/logout">Logout</a>
                            <?php } ?>
                        </li>
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
                                    <a href="/webbanhang/Product/cart" class="btn btn-primary btn-sm w-100">Xem giỏ hàng</a>
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