<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/OrderModel.php');

class OrderController {
    private $db;
    private $orderModel;
    private $accountModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->orderModel = new OrderModel($this->db);
    }

    #region checkout, processCheckout, orderConfirmation
    public function checkout() {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['message'] = "Bạn cần đăng nhập để thanh toán.";
            $_SESSION['message_type'] = "warning";
            include 'app/views/product/cart.php';
            exit();
        } else {
            $cart = $_SESSION['cart'] ?? [];
            include 'app/views/product/checkout.php';
        }
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {     

            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $user_id = $_SESSION['user_id'];
            $cart = $_SESSION['cart'] ?? [];

            if (empty($cart)) {
                $_SESSION['message'] = "Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.";
                $_SESSION['message_type'] = "warning";
                header('Location: /webbanhang/Product/cart');
                exit();
            }

            $this->db->beginTransaction();
            try {
                $query = "INSERT INTO orders (name, phone, address, user_id) VALUES (:name, :phone, :address, :user_id)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                              VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                $this->db->commit();

                $order = [
                    'id' => $order_id,
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                unset($_SESSION['cart']);
                include 'app/views/product/orderConfirmation.php';
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }
    #endregion

    #region orderHistory, orderDetail, list, adminOrderDetail
    public function orderHistory() {
        if (!SessionHelper::isLoggedIn()) {
            $_SESSION['message'] = "Vui lòng đăng nhập để xem lịch sử mua hàng.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/account/login');
            exit;
        }

        if (!SessionHelper::isUser()) {
            $_SESSION['message'] = "Chức năng này chỉ dành cho người dùng.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/product');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $orderHistory = $this->orderModel->getOrderHistory($user_id);
        include_once 'app/views/order/orderHistory.php';
    }

    public function orderDetail() {
        if (!SessionHelper::isLoggedIn() || !SessionHelper::isUser()) {
            $_SESSION['message'] = "Vui lòng đăng nhập với vai trò người dùng để xem chi tiết.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/account/login');
            exit;
        }

        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            $_SESSION['message'] = "ID đơn hàng không hợp lệ.";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/order/orderHistory');
            exit;
        }

        $orderDetails = $this->orderModel->getOrderDetails($order_id);
        include_once 'app/views/order/orderDetail.php';
    }

    // Hiển thị danh sách tất cả đơn hàng (cho admin)
    public function list() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = "Bạn không có quyền truy cập chức năng này.";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/product');
            exit;
        }
    
        // Lấy tham số lọc từ query string
        $phone = isset($_GET['phone']) ? trim($_GET['phone']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
    
        // Gọi phương thức getAllOrders với các tham số lọc
        $orders = $this->orderModel->getAllOrders($phone, $date);
        include 'app/views/order/list.php';
    }

    // Hiển thị chi tiết đơn hàng (cho admin)
    public function adminOrderDetail() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = "Bạn không có quyền truy cập chức năng này.";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/product');
            exit;
        }

        $order_id = $_GET['order_id'] ?? null;
        if (!$order_id) {
            $_SESSION['message'] = "ID đơn hàng không hợp lệ.";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/Order/list');
            exit;
        }

        $orderDetails = $this->orderModel->getOrderDetails($order_id);
        include 'app/views/order/adminOrderDetail.php';
    }
    #endregion
}
?>