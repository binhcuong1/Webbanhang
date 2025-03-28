<?php include 'app/views/shares/header.php'; ?>

<head>
    <style>
        body {
            margin: 0; /* Loại bỏ margin mặc định */
            background: linear-gradient(90deg, #ff6f61, #6b48ff); /* Đặt gradient cho toàn bộ trang */
            min-height: 100vh; /* Đảm bảo chiều cao tối thiểu */
        }
        .card {
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem !important;
            }
            .btn-lg {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
            .form-control-lg {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>

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
    
<!-- Section chính, chiếm toàn màn hình (height: 100vh) với nền gradient -->
<!-- vh-100: Chiều cao bằng 100% chiều cao viewport (màn hình)  -->
<section class="bg-danger" style="background: linear-gradient(90deg, #ff6f61, #6b48ff);">

    <!-- Mục đích: Tạo vùng chứa giới hạn chiều rộng, thêm padding-top và padding-bottom (py-5),
    và chiếm toàn bộ chiều cao (h-100) -->
    <div class="container-fluid py-5 h-100">

        <!-- Mục đích: Tạo hàng (row) và căn giữa form đăng nhập cả theo chiều ngang (justify-content-center)
        và dọc (align-items-center) -->
        <div class="row d-flex justify-content-center align-items-center h-100">

            <!-- Mục đích: Xác định kích thước cột cho form, responsive theo màn hình -->
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <!-- Thẻ chứa form đăng nhập -->
                <!-- Mục đích: Tạo thẻ (card) với nền tối (bg-dark), chữ trắng (text-white), 
                 và bo góc (border-radius: 1rem).
                 style="border-radius: 1rem;": Bo góc 1rem (16px) để đẹp mắt -->
                <div class="card bg-dark text-white" style="border-radius: 1rem;">

                    <!-- Mục đích: Chứa nội dung form, thêm padding (p-5), và căn giữa (text-center)
                     card-body: Phần nội dung của thẻ -->
                    <div class="card-body p-5 text-center">

                        <!-- Mục đích: Tạo form gửi dữ liệu đăng nhập đến hàm checkLogin -->
                        <form action="/webbanhang/account/checklogin" method="post">
                            <div>
                                <h2 class="fw-bold mb-1 text-uppercase">
                                    Login
                                </h2>
                                <p class="text-white-50 mb-4">
                                    Please enter your login and password!
                                </p>

                                <!-- Input Username -->
                                <div class="form-outline mb-3">
                                    <input type="text" name="username" class="form-control" id="typeEmailX" placeholder="Username"/>
                                    
                                </div>

                                <!-- Input Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" name="password" class="form-control" id="typePasswordX" placeholder="Password"/>
                                </div>

                                <!-- Quên mật khẩu -->
                                <p class="small mb-5">
                                    <a href="/webbanhang/account/forgotPassword" class="text-white-50">
                                        Forgot password?
                                    </a>
                                </p>

                                <!-- Nút Đăng nhập -->
                                <button class="btn btn-outline-light btn-lg px-5" type="submit">
                                    Login
                                </button>

                                <!-- Đăng ký -->
                                <div class="mt-5">
                                    <p class="mb-0">
                                        Don't have an account? 
                                        <a href="/webbanhang/account/register" class="text-white-50 fw-bold">
                                            Sign Up
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Thẻ chứa form đăng nhập -->

            </div>
        </div>
    </div>

</section>

<?php include 'app/views/shares/footer.php'; ?>