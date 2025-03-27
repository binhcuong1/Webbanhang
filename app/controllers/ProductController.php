<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/services/ImageUploader.php';
require_once 'app/models/UserModel.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    private $db;
    private $imageUploader;
    private $userModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->userModel = new UserModel($this->db);
        $this->imageUploader = new ImageUploader();
    }

    public function index() {
        // Kiểm tra nếu người dùng là admin
        if (SessionHelper::isAdmin()) {
            // Lấy dữ liệu thống kê cho admin
            $totalProducts = $this->productModel->getTotalProducts();
            $totalCategories = $this->categoryModel->getTotalCategories();
            $totalUsers = $this->userModel->getTotalUsers();

            // Hiển thị bảng điều khiển quản trị
            include 'app/views/product/admin_dashboard.php';
        } else {
            // Logic hiện tại để hiển thị danh sách sản phẩm cho người dùng
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 10;
            $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
            $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : '';
            $rating = isset($_GET['rating']) && in_array($_GET['rating'], ['1', '2', '3', '4', '5']) ? (int)$_GET['rating'] : null;

            // Xây dựng truy vấn cơ bản
            $query = "
                SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name,
                       COALESCE((SELECT AVG(rating) FROM reviews r WHERE r.product_id = p.id), 0) as average_rating
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
        
            // Thêm điều kiện lọc theo đánh giá trung bình (chính xác bằng giá trị rating)
            if ($rating !== null) {
                $query .= " HAVING average_rating = :rating";
                $params[':rating'] = $rating;
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
                if ($key === ':rating') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                }
            }
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        
            // Đếm tổng số sản phẩm để tính tổng trang
            $countQuery = "
                SELECT COUNT(*) 
                FROM product p
                LEFT JOIN category c ON c.id = p.category_id
                WHERE 1=1
            ";
            if ($category_id) {
                $countQuery .= " AND p.category_id = :category_id";
            }
            if ($rating !== null) {
                $countQuery .= " AND COALESCE((SELECT AVG(rating) FROM reviews r WHERE r.product_id = p.id), 0) = :rating";
            }
            $countStmt = $this->db->prepare($countQuery);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value, PDO::PARAM_INT);
            }
            $countStmt->execute();
            $totalProducts = $countStmt->fetchColumn();
        
            $totalPages = ceil($totalProducts / $perPage);
        
            // Lấy danh sách danh mục để hiển thị trong bộ lọc
            $categories = $this->categoryModel->getCategories();
        
            // Hiển thị danh sách sản phẩm cho người dùng
            include 'app/views/product/list.php';
        }
    }
    
    public function list() {
        // Logic hiện tại để hiển thị danh sách sản phẩm cho người dùng
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['asc', 'desc']) ? $_GET['sort'] : '';
        $rating = isset($_GET['rating']) && in_array($_GET['rating'], ['1', '2', '3', '4', '5']) ? (int)$_GET['rating'] : null;
        
        // Xây dựng truy vấn cơ bản
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name,
                   COALESCE((SELECT AVG(rating) FROM reviews r WHERE r.product_id = p.id), 0) as average_rating
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
    
        // Thêm điều kiện lọc theo đánh giá trung bình (chính xác bằng giá trị rating)
        if ($rating !== null) {
            $query .= " HAVING average_rating = :rating";
            $params[':rating'] = $rating;
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
            if ($key === ':rating') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    
        // Đếm tổng số sản phẩm để tính tổng trang
        $countQuery = "
            SELECT COUNT(*) 
            FROM product p
            LEFT JOIN category c ON c.id = p.category_id
            WHERE 1=1
        ";
        if ($category_id) {
            $countQuery .= " AND p.category_id = :category_id";
        }
        if ($rating !== null) {
            $countQuery .= " AND COALESCE((SELECT AVG(rating) FROM reviews r WHERE r.product_id = p.id), 0) = :rating";
        }
        $countStmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $countStmt->execute();
        $totalProducts = $countStmt->fetchColumn();
    
        $totalPages = ceil($totalProducts / $perPage);
    
        // Lấy danh sách danh mục để hiển thị trong bộ lọc
        $categories = $this->categoryModel->getCategories();
    
        // Hiển thị danh sách sản phẩm cho người dùng
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

    #region Đánh giá sản phẩm: addReview, getReviews, getAverageRating
    // Thêm đánh giá mới
    public function addReview() {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['message'] = "Vui lòng đăng nhập để đánh giá sản phẩm.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/account/login');
            exit;
        }

        // Xử lý khi form được gửi
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $user_id = $_SESSION['user_id'];
            $rating = $_POST['rating'] ?? null;
            $comment = $_POST['comment'] ?? '';

            // Kiểm tra dữ liệu đầu vào
            if (!$product_id || !$rating || !in_array($rating, [1, 2, 3, 4, 5])) {
                $_SESSION['message'] = "Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.";
                $_SESSION['message_type'] = "danger";
                header('Location: /webbanhang/Product/show/' . $product_id);
                exit;
            }

            // Thêm đánh giá vào cơ sở dữ liệu
            $query = "INSERT INTO reviews (product_id, user_id, rating, comment) 
                      VALUES (:product_id, :user_id, :rating, :comment)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Đánh giá của bạn đã được gửi thành công.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.";
                $_SESSION['message_type'] = "danger";
            }

            // Chuyển hướng về trang chi tiết sản phẩm
            header('Location: /webbanhang/Product/show/' . $product_id);
            exit;
        }
    }

    // Lấy danh sách đánh giá của một sản phẩm
    public function getReviews($product_id) {
        $query = "SELECT r.*, u.username 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.product_id = :product_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAverageRating($product_id) {
        $query = "SELECT AVG(rating) as average_rating 
                  FROM reviews 
                  WHERE product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result->average_rating === null) {
            return 0; // Trả về 0 nếu không có đánh giá
        }
        
        return round($result->average_rating, 1);
    }
    #endregion
}
?>