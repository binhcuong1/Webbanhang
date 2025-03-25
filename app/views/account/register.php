<?php include 'app/views/shares/header.php'; ?>

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

<!-- Section chính, không cần background gradient nữa -->
<section class="py-5" style="min-height: 100vh;">
    <div class="container-fluid py-5">
        <div class="row d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php
                        if (isset($errors)) {
                            echo '<div class="alert alert-danger mt-3">';
                            echo '<ul class="mb-0">';
                            foreach ($errors as $err) {
                                echo "<li>$err</li>";
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>

                        <form action="/webbanhang/account/save" method="post">
                            <div>
                                <h2 class="fw-bold mb-1 text-uppercase">Register</h2>
                                <p class="text-white-50 mb-4">Please enter your registration details!</p>

                                <!-- Input Username -->
                                <div class="form-outline mb-3">
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Username" required>
                                </div>

                                <!-- Input Fullname -->
                                <div class="form-outline mb-3">
                                    <input type="text" class="form-control form-control-lg" id="fullname" name="fullname" placeholder="Fullname" required>
                                </div>

                                <!-- Input Password -->
                                <div class="form-outline mb-3">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                </div>

                                <!-- Input Confirm Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" class="form-control form-control-lg" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                                </div>

                                <!-- Nút Đăng ký -->
                                <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>

                                <!-- Đăng nhập -->
                                <div class="mt-5">
                                    <p class="mb-0">
                                        Already have an account? 
                                        <a href="/webbanhang/account/login" class="text-white-50 fw-bold">Login</a>
                                    </p>
                                    <p class="mt-2"><a href="/webbanhang/product" class="text-white-50">Back to Home</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>