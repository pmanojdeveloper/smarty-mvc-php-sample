<?php
require_once 'config.php';
require_once 'models/ItemModel.php';

class ItemController {
    private $smarty;
    private $itemModel;
    private $base_url; 

    public function __construct() {
        $config = require('config.php');
        $this->smarty = $config['smarty'];
        $this->itemModel = new ItemModel($config['db']);
        $this->base_url = dirname($_SERVER['SCRIPT_NAME']); 
    }

    public function index() {
        $items = $this->itemModel->getAllItems();
        $this->smarty->assign('items', $items);
        $this->smarty->assign('base_url', $this->base_url); 
        $this->smarty->display('item_list.tpl');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->itemModel->createItem($name, $description);
            $this->smarty->assign('base_url', $this->base_url);
            header('Location: ' . $this->base_url); 
            exit;
        } else {
            $this->smarty->assign('base_url', $this->base_url);
            $this->smarty->display('item_form.tpl');
        }
    }
    

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $this->itemModel->updateItem($id, $name, $description);
            $this->smarty->assign('base_url', $this->base_url); 
            header('Location: ' . $this->base_url); 
            exit;
        } else {
            $item = $this->itemModel->getItemById($id);
            $this->smarty->assign('base_url', $this->base_url); 
            $this->smarty->assign('item', $item);
            $this->smarty->display('item_form.tpl');
        }
    }

    public function delete($id) {
        $this->smarty->assign('base_url', $this->base_url); 
        $this->itemModel->deleteItem($id);
        header('Location: ' . $this->base_url); 
        exit;
    }
}
?>
