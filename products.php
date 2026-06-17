<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'products';

require_once 'controllers/ProductsController.php';
require_once 'models/Products.php';
$PC = new ProductsController();
$error = null;

// Route: Handle Delete
if (isset($_GET['delete'])) {
  $product_id = intval($_GET['delete']);
  if ($PC->delete($product_id) === true) {
    echo "<script>alert('Product deleted successfully!');location.href='products.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error deleting product!');location.href='products.php';</script>";
    exit();
  }
}

// Route: Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
  $product_name = htmlspecialchars($_POST['product_name'] ?? '');
  $price = floatval($_POST['price'] ?? 0);
  $stock = intval($_POST['stock'] ?? 0);

  if (empty($product_name)) {
    $error = "Product name is required!";
  } elseif ($price <= 0) {
    $error = "Price must be greater than 0!";
  } else {
    $product = new Product($product_name, $price, $stock);
    $res = $PC->save($product);
    if ($res === true) {
      echo "<script>alert('Product added successfully!');location.href='products.php';</script>";
      exit();
    } else {
      $error = "Error adding product: $res";
    }
  }
}

// Route: Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
  $product_id = intval($_POST['product_id']);
  $product_name = htmlspecialchars($_POST['product_name'] ?? '');
  $price = floatval($_POST['price'] ?? 0);
  $stock = intval($_POST['stock'] ?? 0);

  if (empty($product_name)) {
    $error = "Product name is required!";
  } elseif ($price <= 0) {
    $error = "Price must be greater than 0!";
  } else {
    $product = new Product($product_name, $price, $stock, $product_id);
    $res = $PC->save($product);
    if ($res === true) {
      echo "<script>alert('Product Edited Successfully!');location.href='products.php';</script>";
      exit();
    } else {
      $error = "Error Editing Product: $res";
    }
  }
}

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$productsList = $PC->searchProducts($search);

$edit_product = null;
if (isset($_GET['edit'])) {
  $product_id = intval($_GET['edit']);
  $productModel = $PC->findById($product_id);
  if ($productModel) {
    $edit_product = [
      'product_id' => $productModel->productId,
      'product_name' => $productModel->productName,
      'price' => $productModel->price,
      'stock' => $productModel->stock
    ];
  }
}

include 'views/products_view.php';
