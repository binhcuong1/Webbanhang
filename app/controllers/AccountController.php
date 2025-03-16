<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui long nhap userName!";
            }

            if (empty($fullName)) {
                $errors['fullname'] = "Vui long nhap fullName!";
            }

            if (empty($password)) {
                $errors['password'] = "Vui long nhap password!";
            }

            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }

            // Kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tai khoan nay da co nguoi dang ky!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password);
            }

            if ($result) {
                header('Location: /webbanhang/account/login');
            }
        }
    }
    
    function logout() {
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        session_destroy();
        header('Location: /webbanhang/product');
    }
    
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
    
            $account = $this->accountModel->getAccountByUsername($username);
    
            if ($account) {
                $pwd_hashed = $account->password;
    
                if (password_verify($password, $pwd_hashed)) {
                    session_start();
                    $_SESSION['user_id'] = $account->id; // Đảm bảo lưu user_id
                    $_SESSION['username'] = $account->username;

                    // Lưu role
                    if (isset($account->role))
                        $_SESSION['role'] = $account->role;

                    header('Location: /webbanhang/product');
                    exit;
                } else {
                    $_SESSION['message'] = "Password incorrect.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: /webbanhang/account/login');
                }
            } else {
                $_SESSION['message'] = "Bao loi khong tim thay tai khoan";
                $_SESSION['message_type'] = "danger";
                header('Location: /webbanhang/account/login');
            }
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Vui lòng đăng nhập để xem thông tin.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/account/login');
            exit;
        }

        $user = $this->accountModel->getAccountById($_SESSION['user_id']);
        include 'app/views/account/profile.php';
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Vui lòng đăng nhập để cập nhật thông tin.";
            $_SESSION['message_type'] = "warning";
            header('Location: /webbanhang/account/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = $_POST['fullname'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            $user = $this->accountModel->getAccountById($_SESSION['user_id']);
            if (!$user || !password_verify($currentPassword, $user->password)) {
                $errors['current_password'] = "Mật khẩu hiện tại không đúng.";
            }

            if (!empty($newPassword) && $newPassword !== $confirmPassword) {
                $errors['confirm_password'] = "Mật khẩu mới và xác nhận không khớp.";
            }

            if (empty($errors)) {
                $updateData = ['fullname' => $fullName];
                if (!empty($newPassword)) {
                    $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
                }
                $result = $this->accountModel->updateAccount($_SESSION['user_id'], $updateData);

                if ($result) {
                    $_SESSION['message'] = "Cập nhật thông tin thành công.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Cập nhật thông tin thất bại.";
                    $_SESSION['message_type'] = "danger";
                }
                header('Location: /webbanhang/account/profile');
                exit;
            } else {
                $user = $this->accountModel->getAccountById($_SESSION['user_id']);
                include 'app/views/account/profile.php';
            }
        } else {
            $user = $this->accountModel->getAccountById($_SESSION['user_id']);
            include 'app/views/account/profile.php';
        }
    }
}