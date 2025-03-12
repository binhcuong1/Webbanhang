    <?php 
    require_once('app/config/database.php');
    require_once('app/models/ProductModel.php');
    require_once('app/models/CategoryModel.php');

    class ProductController {

        private $productModel;
        private $db;

        public function __construct() {
            $this->db = (new Database())->getConnection();
            $this->productModel = new ProductModel($this->db);
        }

        public function index() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 5; // Số sản phẩm mỗi trang
            $products = $this->productModel->getProductsPaginated($page, $perPage);
            $totalProducts = $this->productModel->getTotalProducts();
            $totalPages = ceil($totalProducts / $perPage);
            include 'app/views/product/list.php';
        }

        #region CRUD

        public function add() {
            $categories = (new CategoryModel($this->db))->getCategories();
            include_once 'app/views/product/add.php';
        }

        public function edit($id) {
            $product = $this->productModel->getProductByID($id);
            $categories = (new CategoryModel($this->db))->getCategories();

            if ($product) 
                include 'app/views/product/edit.php';
            else
                echo "Không thấy sản phẩm";
        }

        public function update() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category_id = $_POST['category_id'];

                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                } else {
                    $image = $_POST['existing_image'];
                }

                $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

                if ($edit) {
                    header('Location: /webbanhang/Product');
                } else {
                    echo "Đã xảy ra lỗi khi lưu sản phẩm.";
                }  
            }
        }

        public function delete($id) {
            if ($this->productModel->deleteProduct($id)) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi xóa sản phẩm.";
            }
        }
        
        #endregion

        public function show($id) {
            $product = $this->productModel->getProductById($id);

            if ($product) 
                include 'app/views/product/show.php';
            else
                echo "Không thấy sản phẩm.";
        }

        public function save() {
            $name = "";
            $description = "";
            $price = "";
            $category_id = "";
            $error = [];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = isset($_POST['name']) ? $_POST['name'] : '';
                $description = isset($_POST['description']) ? $_POST['description'] : '';
                $price = isset($_POST['price']) ? $_POST['price'] : '';
                $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;

                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                } else {
                    $image = "";
                }
            }
            else
            {
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
                return;
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $error = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }

        public function search() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 5; // Số sản phẩm mỗi trang
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
            
            if ($keyword) {
                // Đếm tổng số sản phẩm khớp với từ khóa
                $totalProducts = $this->productModel->countSearchProducts($keyword);
                $products = $this->productModel->searchProductsPaginated($keyword, $page, $perPage);
            } else {
                // Nếu không có từ khóa, dùng toàn bộ sản phẩm
                $totalProducts = $this->productModel->getTotalProducts();
                $products = $this->productModel->getProductsPaginated($page, $perPage);
            }
            
            $totalPages = ceil($totalProducts / $perPage);
            include 'app/views/product/list.php';
        }

        public function cart()
        {
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            include 'app/views/product/cart.php';
        }

        public function checkout() {
            $cart = $_SESSION['cart'] ?? []; // Lấy giỏ hàng từ session
            include 'app/views/product/checkout.php'; // Truyền $cart vào view
        }

        public function processCheckout() {
        
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];

                $this->db->beginTransaction();

                try {
                    // Lưu thông tin đơn hàng vào bảng orders
                    $query = "
                        INSERT INTO orders (name, phone, address) 
                        VALUES (:name, :phone, :address)
                        ";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':address', $address);
                    $stmt->execute();
                    $order_id = $this->db->lastInsertId();

                    // Lưu chi tiết đơn hàng vào bảng order_details
                    $cart = $_SESSION['cart'];
                    foreach ($cart as $product_id => $item) {
                    $query = "
                        INSERT INTO order_details (order_id, product_id, quantity, price) 
                        VALUES (:order_id, :product_id, :quantity, :price)
                        ";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                    }

                    // Commit giao dịch
                    $this->db->commit();

                } catch (Exception $e) {
                    // Rollback giao dịch nếu có lỗi
                    $this->db->rollBack();
                    echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
                }
                
                    // Lấy giỏ hàng từ session
                $cart = $_SESSION['cart'] ?? [];
                
                // Kiểm tra nếu giỏ hàng trống
                if (empty($cart)) {
                    $_SESSION['message'] = "Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.";
                    $_SESSION['message_type'] = "warning";
                    header('Location: /webbanhang/Product/cart');
                    exit();
                }
            
                // Lấy dữ liệu từ form
                $order = [
                    'id' => uniqid(), // Tạo mã đơn hàng tạm
                    'name' => $_POST['name'] ?? 'Khách hàng',
                    'phone' => $_POST['phone'] ?? 'N/A',
                    'address' => $_POST['address'] ?? 'N/A',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            
                // Lưu đơn hàng vào database (nếu có model)
                // Ví dụ: $this->model->saveOrder($order, $cart);
            
                // Truyền dữ liệu vào view orderConfirmation.php
                include 'app/views/product/orderConfirmation.php';
            
                // Reset giỏ hàng sau khi xử lý
                unset($_SESSION['cart']);
            }
        }

        public function orderConfirmation()
        {
            include 'app/views/product/orderConfirmation.php';
        }

        

        #region UploadImage
        private function uploadImage($file) {
    $target_dir = "uploads/";
    // Kiểm tra và tạo thư mục nếu chưa tồn tại
    if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Kiểm tra xem file có phải là hình ảnh không
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
    throw new Exception("File không phải là hình ảnh.");
    }
    // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
    if ($file["size"] > 10 * 1024 * 1024) {
    throw new Exception("Hình ảnh có kích thước quá lớn.");
    }
    // Chỉ cho phép một số định dạng hình ảnh nhất định
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
    "jpeg" && $imageFileType != "gif") {
    throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
    }
    // Lưu file
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }


        
        #endregion

        #region Cart

            public function addToCart($id)
            {
                $product = $this->productModel->getProductById($id);
                if (!$product) {
                    echo "Không tìm thấy sản phẩm.";
                    return;
                }
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity']++;
                } else {
                    $_SESSION['cart'][$id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $product->image
                    ];
                }
                // Thêm thông báo (tùy chọn)
                $_SESSION['message'] = "Đã thêm sản phẩm vào giỏ hàng.";
                $_SESSION['message_type'] = "success";
                        
                // Quay lại trang danh sách sản phẩm thay vì chuyển đến trang giỏ hàng
                header('Location: /webbanhang/Product/');
            }

        public function destroy() {
            session_destroy();
            header('Location: /webbanhang/Product/');
        }

        public function removeFromCart($id) {
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                $_SESSION['message'] = "Sản phẩm đã được xóa khỏi giỏ hàng.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Sản phẩm không tồn tại trong giỏ hàng.";
                $_SESSION['message_type'] = "warning";
            }
            header('Location: /webbanhang/Product/cart');
            exit();
        }

        public function updateCart() {
            session_start();
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                $action = isset($_POST['action']) ? $_POST['action'] : '';
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        
                // Kiểm tra sản phẩm có trong giỏ hàng không
                if (isset($_SESSION['cart'][$id])) {
                    // Cập nhật số lượng
                    if ($action === 'increase') {
                        $_SESSION['cart'][$id]['quantity'] += 1;
                    } elseif ($action === 'decrease') {
                        $_SESSION['cart'][$id]['quantity'] -= 1;
                        // Nếu số lượng <= 0, xóa sản phẩm
                        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                            unset($_SESSION['cart'][$id]);
                            $_SESSION['message'] = "Sản phẩm đã được xóa khỏi giỏ hàng.";
                            $_SESSION['message_type'] = "success";
                        }
                    } elseif ($quantity > 0) {
                        // Nếu người dùng nhập số lượng trực tiếp
                        $_SESSION['cart'][$id]['quantity'] = $quantity;
                    }
        
                    // Nếu số lượng hợp lệ, cập nhật thông báo
                    if (isset($_SESSION['cart'][$id])) {
                        $_SESSION['message'] = "Cập nhật số lượng thành công.";
                        $_SESSION['message_type'] = "success";
                    }
                } else {
                    $_SESSION['message'] = "Sản phẩm không tồn tại trong giỏ hàng.";
                    $_SESSION['message_type'] = "warning";
                }
            }
        
            // Chuyển hướng về trang giỏ hàng
            header('Location: /webbanhang/Product/cart');
            exit();
        }

        #endregion
    }
    ?>