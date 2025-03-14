<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

class CartController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function cart() {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }

    public function addToCart($id) {
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
        $_SESSION['message'] = "Đã thêm sản phẩm vào giỏ hàng.";
        $_SESSION['message_type'] = "success";
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
        header('Location: /webbanhang/Cart/cart'); 
        exit();
    }

    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $action = $_POST['action'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 0);

            if (isset($_SESSION['cart'][$id])) {
                if ($action === 'increase') {
                    $_SESSION['cart'][$id]['quantity']++;
                } elseif ($action === 'decrease') {
                    $_SESSION['cart'][$id]['quantity']--;
                    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                        unset($_SESSION['cart'][$id]);
                        $_SESSION['message'] = "Sản phẩm đã được xóa khỏi giỏ hàng.";
                        $_SESSION['message_type'] = "success";
                    }
                } elseif ($quantity > 0) {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['message'] = "Cập nhật số lượng thành công.";
                    $_SESSION['message_type'] = "success";
                }
            } else {
                $_SESSION['message'] = "Sản phẩm không tồn tại trong giỏ hàng.";
                $_SESSION['message_type'] = "warning";
            }
        }
        header('Location: /webbanhang/Cart/cart');
    }
}
?>