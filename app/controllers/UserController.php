<?php
require_once 'app/models/UserModel.php';
require_once 'app/models/RoleLogModel.php';
require_once 'app/helpers/SessionHelper.php';

class UserController {
    private $userModel;
    private $roleLogModel;

    public function __construct() {
        $this->userModel = new UserModel((new Database())->getConnection());
        $this->roleLogModel = new RoleLogModel((new Database())->getConnection());
    }

    // Trang quản lý vai trò
    public function manageRoles() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/Product');
            exit;
        }

        $users = $this->userModel->getAllUsers();
        include 'app/views/user/manage_roles.php';
    }

    // Cập nhật vai trò
    public function updateRole($userId) {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = "Bạn không có quyền thực hiện hành động này!";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/Product');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newRole = $_POST['new_role'];

            // Lấy thông tin người dùng để lấy vai trò cũ
            $user = $this->userModel->getUserById($userId);
            $oldRole = $user->role;

            // Cập nhật vai trò mới
            $this->userModel->updateRole($userId, $newRole);

            // Ghi log thay đổi vai trò
            $adminId = SessionHelper::getUserId();
            $this->roleLogModel->logRoleChange($adminId, $userId, $oldRole, $newRole);

            $_SESSION['message'] = "Cập nhật vai trò thành công!";
            $_SESSION['message_type'] = "success";
            header('Location: /webbanhang/User/manageRoles');
            exit;
        }
    }

    // Trang xem lịch sử thay đổi vai trò
    public function roleLog() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = "Bạn không có quyền truy cập trang này!";
            $_SESSION['message_type'] = "danger";
            header('Location: /webbanhang/Product');
            exit;
        }

        $logs = $this->roleLogModel->getRoleLogs();
        include 'app/views/user/role_log.php';
    }
}