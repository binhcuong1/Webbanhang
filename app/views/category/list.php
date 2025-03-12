<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4 mt-3" style="color: #e63946; font-weight: bold;">
        Danh sách danh mục
    </h1>

    <!-- Nút thêm mới -->
    <div class="text-end mb-4">
        <a href="/webbanhang/Category/add" class="btn btn-custom">Thêm danh mục mới</a>
    </div>

    <!-- Danh sách danh mục -->
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <h5 class="card-title">
                            <a href="/webbanhang/Category/show/<?php echo $category->id; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="/webbanhang/Category/edit/<?php echo $category->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">Xóa</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>