<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">Thêm sản phẩm mới</h1>

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

    <!-- Form thêm sản phẩm -->
    <form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data">
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm:</label>
            <div class="col-sm-10">
                <input name="name" id="name" type="text" class="form-control" 
                       placeholder="Nhập tên sản phẩm..." required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="price" class="col-sm-2 col-form-label">Giá:</label>
            <div class="col-sm-10">
                <input type="number" id="price" name="price" class="form-control" step="0.01" 
                       placeholder="Nhập giá sản phẩm..." required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Mô tả:</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" rows="4" 
                          placeholder="Nhập mô tả sản phẩm..." required></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label for="category_id" class="col-sm-2 col-form-label">Danh mục:</label>
            <div class="col-sm-10">
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="" disabled selected>Chọn danh mục...</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">Hình ảnh:</label>
            <div class="col-sm-10">
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-custom">Thêm sản phẩm</button>
            <a href="/webbanhang/Product/index" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>