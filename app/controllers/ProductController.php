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
        $products = $this->productModel->getProductsPaginated($page, $perPage);
        $totalProducts = $this->productModel->getTotalProducts();
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
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
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