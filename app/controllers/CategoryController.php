<?php 
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    #region CRUD
    public function index() {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function list() {
        $categories  = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            include_once 'app/views/category/add.php';
        }
    }

    public function save() {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['message'] = 'Bạn không có quyền truy cập chức năng này!';
            $_SESSION['message_type'] = 'danger';
            header("Location: /webbanhang/product");
            exit;
        } else {
            $name = "";
            $description = "";
            $error = [];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = isset($_POST['name']) ? $_POST['name'] : '';
                $description = isset($_POST['description']) ? $_POST['description'] : '';
            }
            else
            {
                include 'app/views/category/add.php';
                return;
            }

            $result = $this->categoryModel->addCategory($name, $description);

            if (is_array($result)) {
                $error = $result;
                include 'app/views/category/add.php';
            } else {
                header('Location: /webbanhang/Category');
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
            $category = $this->categoryModel->getCategoryByID($id);

            if ($category) 
                include 'app/views/category/edit.php';
            else
                echo "Không thấy danh mục";
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

                $edit = $this->categoryModel->updateCategory($id, $name, $description);

                if ($edit) {
                    header('Location: /webbanhang/Category');
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
            if ($this->categoryModel->deleteCategory($id)) {
                header('Location: /webbanhang/Category');
            } else {
                echo "Đã xảy ra lỗi khi xóa danh mục.";
            }
        }
    }
    #endregion
}

?>