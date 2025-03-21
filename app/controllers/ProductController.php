<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/services/ImageUploader.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $db;
    private $imageUploader;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->imageUploader = new ImageUploader();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5;
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : '';
    
        // Xây dựng truy vấn cơ bản
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
            FROM product p
            LEFT JOIN category c ON c.id = p.category_id
            WHERE 1=1
        ";
        $params = [];
    
        // Thêm điều kiện lọc theo category_id
        if ($category_id) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
    
        // Thêm sắp xếp theo giá
        if ($sort) {
            $query .= " ORDER BY p.price " . ($sort === 'asc' ? 'ASC' : 'DESC');
        }
    
        // Thêm phân trang
        $offset = ($page - 1) * $perPage;
        $query .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        // Bind các tham số lọc
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        // Đếm tổng số sản phẩm để tính tổng trang (không cần sắp xếp cho đếm)
        $countQuery = "
            SELECT COUNT(*) 
            FROM product p
            WHERE 1=1
        ";
        if ($category_id) {
            $countQuery .= " AND p.category_id = :category_id";
        }
        $countStmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $countStmt->execute();
        $totalProducts = $countStmt->fetchColumn();
    
        $totalPages = ceil($totalProducts / $perPage);
        include 'app/views/product/list.php';
    }
    
    public function add() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            $categories = $this->categoryModel->getCategories();
            include 'app/views/product/add.php';
        }
    }

    public function save() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            $error = [];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'] ?? '';
                $description = $_POST['description'] ?? '';
                $price = $_POST['price'] ?? '';
                $category_id = $_POST['category_id'] ?? null;
                $image = $_FILES['image']['error'] == 0 ? $this->imageUploader->upload($_FILES['image']) : '';

                $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
                if (is_array($result)) {
                    $error = $result;
                    $categories = $this->categoryModel->getCategories();
                    include 'app/views/product/add.php';
                } else {
                    header('Location: /webbanhang/Product');
                }
            } else {
                $this->add();
            }
        }
    }

    public function edit($id) {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getCategories();
            if ($product) {
                include 'app/views/product/edit.php';
            } else {
                echo "Không thấy sản phẩm";
            }
        }
    }

    public function update() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category_id = $_POST['category_id'];
                $image = $_FILES['image']['error'] == 0 ? $this->imageUploader->upload($_FILES['image']) : $_POST['existing_image'];

                if ($this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image)) {
                    header('Location: /webbanhang/Product');
                } else {
                    echo "Đã xảy ra lỗi khi lưu sản phẩm.";
                }
            }
        }
    }

    public function delete($id) {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            if ($this->productModel->deleteProduct($id)) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi xóa sản phẩm.";
            }
        }
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $_SESSION['message'] = 'Sản phẩm không tồn tại.';
            $_SESSION['message_type'] = 'danger';
            header('Location: /webbanhang/Product');
            exit();
        }

        // Lấy sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
        $relatedProducts = $this->productModel->getRelatedProducts($product->category_id, $id, 4);

        include 'app/views/product/show.php';
    }

    public function search() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5;
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if ($keyword) {
            $totalProducts = $this->productModel->countSearchProducts($keyword);
            $products = $this->productModel->searchProductsPaginated($keyword, $page, $perPage);
        } else {
            $totalProducts = $this->productModel->getTotalProducts();
            $products = $this->productModel->getProductsPaginated($page, $perPage);
        }
        $totalPages = ceil($totalProducts / $perPage);
        include 'app/views/product/list.php';
    }
}
?>