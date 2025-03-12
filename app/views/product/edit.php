<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <h1 class="text-center mb-4" style="color: #e63946; font-weight: bold;">
        Sửa sản phẩm
    </h1>

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
    <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
        
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Tên sản phẩm:</label>
            <div class="col-sm-10">
                <input type="text" id="name" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="price" class="col-sm-2 col-form-label">Giá:</label>
            <div class="col-sm-10">
                <input type="number" id="price" name="price" class="form-control" step="0.01" 
                       value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Mô tả:</label>
            <div class="col-sm-10">
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <label for="category_id" class="col-sm-2 col-form-label">Danh mục:</label>
            <div class="col-sm-10">
                <select id="category_id" name="category_id" class="form-select" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
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
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($product->image): ?>
                    <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="Product Image" class="mt-2 rounded" style="max-width: 150px; border: 1px solid #ced4da;">
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-custom">Lưu thay đổi</button>
            <a href="/webbanhang/Product/" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>