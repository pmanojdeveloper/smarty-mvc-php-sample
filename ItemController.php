<?php
require_once 'config.php';
require_once 'models/ItemModel.php';

class ItemController
{
    private $smarty;
    private $itemModel;
    private $base_url;

    public function __construct()
    {
        $config = require('config.php');
        $this->smarty = $config['smarty'];
        $this->itemModel = new ItemModel($config['db']);
        $this->base_url = dirname($_SERVER['SCRIPT_NAME']);
    }

    public function index()
    {
        if (!empty($_GET['get_items'])) {
            $items = $this->itemModel->getAllItems();
            echo json_encode(['items' => $items]);
            exit;
        }

        $items = $this->itemModel->getAllItems();
        $this->smarty->assign('items', $items);
        $this->smarty->assign('base_url', $this->base_url);
        $this->smarty->display('item_list.tpl');
    }



    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];

            $file_path = null;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'controllers/uploaded/files/';
                $file_name = $_FILES['file']['name'];
                $file_path = $upload_dir . $file_name;


                if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {

                    $file_path = null;
                    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
                    exit;
                }
            }

            $item_id = $this->itemModel->createItem($name, $description, $file_path);
            if ($item_id) {
                $created_item = $this->itemModel->getItemById($item_id);
                echo json_encode(['success' => true, 'message' => 'Item created successfully', 'item' => $created_item]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to create item']);
                exit;
            }
        }

        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $file_path = null;

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'controllers/uploaded/files/';
                $file_name = $_FILES['file']['name'];
                $file_path = $upload_dir . $file_name;

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
                    exit;
                }
            } else {
                $existingItem = $this->itemModel->getItemById($id);
                $file_path = $existingItem['file_path'];
            }

            $success = $this->itemModel->updateItem($id, $name, $description, $file_path);
            if ($success) {
                $updated_item = $this->itemModel->getItemById($id);
                echo json_encode(['success' => true, 'message' => 'Item updated successfully', 'item' => $updated_item]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update item']);
                exit;
            }
        } else {
            $item = $this->itemModel->getItemById($id);
            if ($item) {
                echo json_encode(['item' => $item]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'Item not found']);
                exit;
            }
        }

        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
    }


    public function delete($id)
    {
        $success = $this->itemModel->deleteItem($id);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Item deleted successfully', 'id' => $id]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete item']);
            exit;
        }
    }
}
