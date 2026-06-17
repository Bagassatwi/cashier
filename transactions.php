<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}

$page = $title = 'transactions';

// Dependancy Injection / Imports
include 'controllers/TransactionController.php';
include 'controllers/ProductsController.php';
include 'controllers/StoreController.php';
include 'models/Transactions.php';
include 'models/Products.php';

$TC = new TransactionController();
$PC = new ProductsController();
$SC = new StoreController();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
  $product_id = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']);

  $product = $PC->findById($product_id);

  if (!$product) {
    $error = "Product not found!";
  } elseif ($quantity > $product->stock) {
    $error = "Insufficient stock!";
  } else {
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['product_id'] === $product_id) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
      }
    }

    if (!$found) {
      $_SESSION['cart'][] = [
        'product_id' => $product->productId,
        'product_name' => $product->productName,
        'price' => $product->price,
        'quantity' => $quantity
      ];
    }
    header("Location: transactions.php");
    exit();
  }
}

// Route: Handle Remove from Cart
if (isset($_GET['remove_cart'])) {
  $product_id = intval($_GET['remove_cart']);
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['product_id'] === $product_id) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
  header("Location: transactions.php");
  exit();
}

// Route: Handle Save Transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_transaction') {
  $store_id = intval($_POST['store_id']);
  $payment_string = $_POST['payment_type'] ?? 'Cash';

  if (empty($store_id)) {
    $error = "Please select a store!";
  } elseif (count($_SESSION['cart']) === 0) {
    $error = "Cart is empty!";
  } else {
    $payment_type = PaymentType::tryFrom($payment_string) ?? PaymentType::Cash;
    $transactionToSave = new Transaction($store_id, (int)$_SESSION['id_admin'], $payment_type);

    if ($TC->save($transactionToSave, $_SESSION['cart'])) {
      $success = "Transaction processed successfully!";
      $_SESSION['cart'] = [];
    } else {
      $error = "Failed to save transaction.";
    }
  }
}

// Fetch datasets using domain models/controllers
$storesList = $SC->getAllStores();
$productsList = $PC->getAvailableProducts();

// Calculations
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal;

// Include the isolated view template
include 'views/transactions_view.php';
