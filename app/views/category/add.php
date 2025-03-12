<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Thêm danh mục mới</h1>

    <!-- Hiển thị lỗi -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($error as $msg): ?>
                    <li><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form thêm danh mục -->
    <form method="POST" action="/webbanhang/Category/save" enctype="multipart/form-data">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Tên danh mục:</label>
            <div class="col-sm-10">
                <input name="name" id="name" type="text" class="form-control" 
                       placeholder="Nhập tên danh mục..." required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Mô tả:</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" rows="4" 
                          placeholder="Nhập mô tả danh mục..." required></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-custom">Thêm danh mục</button>
            <a href="/webbanhang/Category/index" class="btn btn-secondary">Quay lại danh sách danh mục</a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>