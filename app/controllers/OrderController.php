<?php
require_once 'app/config/database.php';

class OrderController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function checkout() {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/checkout.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $cart = $_SESSION['cart'] ?? [];

            if (empty($cart)) {
                $_SESSION['message'] = "Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.";
                $_SESSION['message_type'] = "warning";
                header('Location: /webbanhang/Product/cart');
                exit();
            }

            $this->db->beginTransaction();
            try {
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
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
}
?>