<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Sửa danh mục</h1>

    <!-- Hiển thị lỗi -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form chỉnh sửa -->
    <form method="POST" action="/webbanhang/Category/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $category->id; ?>">

        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Tên danh mục:</label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Mô tả:</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-custom">Lưu thay đổi</button>
            <a href="/webbanhang/Category/list" class="btn btn-secondary">Quay lại danh sách danh mục</a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>