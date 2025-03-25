<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <h1 class="mb-4 display-5 fw-bold d-flex align-items-center">
        <i class="fas fa-tachometer-alt me-3 text-primary"></i> Bảng điều khiển quản trị
    </h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title display-4"><?php echo $totalProducts; ?></h3>
                    <p class="card-text">Tổng số sản phẩm</p>
                    <a href="/webbanhang/Product/list" class="btn btn-light">Quản lý sản phẩm</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title display-4"><?php echo $totalCategories; ?></h3>
                    <p class="card-text">Tổng số danh mục</p>
                    <a href="/webbanhang/Category/list" class="btn btn-light">Quản lý danh mục</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title display-4"><?php echo $totalUsers; ?></h3>
                    <p class="card-text">Tổng số người dùng</p>
                    <a href="/webbanhang/User/manageRoles" class="btn btn-light">Quản lý người dùng</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-bolt me-2 text-muted"></i> Thao tác nhanh
            </h5>
        </div>
        <div class="card-body">
            <a href="/webbanhang/Product/add" class="btn btn-primary me-2">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm mới
            </a>
            <a href="/webbanhang/Category/add" class="btn btn-success me-2">
                <i class="fas fa-plus me-1"></i> Thêm danh mục mới
            </a>
            <a href="/webbanhang/Order/list" class="btn btn-info">
                <i class="fas fa-shopping-bag me-1"></i> Lịch sử đơn hàng
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>