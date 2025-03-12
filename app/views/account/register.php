<?php include 'app/views/shares/header.php'; ?>

<section class="bg-danger" style="background: linear-gradient(90deg, #ff6f61, #6b48ff); height: 100vh;">
    <div class="container-fluid py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
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

                        <h2 class="fw-bold mb-1 text-uppercase">Register</h2>
                        <p class="text-white-50 mb-4">Please enter your registration details!</p>

                        <form class="user" action="/webbanhang/account/save" method="post">
                            <div class="form-outline mb-3">
                                <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Username" required>
                                <label class="form-label" for="username">Username</label>
                            </div>

                            <div class="form-outline mb-3">
                                <input type="text" class="form-control form-control-lg" id="fullname" name="fullname" placeholder="Fullname" required>
                                <label class="form-label" for="fullname">Fullname</label>
                            </div>

                            <div class="form-outline mb-3">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                <label class="form-label" for="password">Password</label>
                            </div>

                            <div class="form-outline mb-4">
                                <input type="password" class="form-control form-control-lg" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                                <label class="form-label" for="confirmpassword">Confirm Password</label>
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-custom btn-lg px-5" type="submit">Register</button>
                            </div>

                            <div class="mt-4">
                                <p class="mb-0">Already have an account? <a href="/webbanhang/account/login" class="text-white-50 fw-bold">Login</a></p>
                                <p class="mt-2"><a href="/webbanhang/Product/index" class="text-white-50">Back to Home</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>