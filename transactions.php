<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}

$page = $title = 'transactions';

include_once 'controllers/TransactionController.php';
include_once 'controllers/ProductsController.php';
include_once 'controllers/StoreController.php';
include_once 'models/Transactions.php';
include_once 'models/Products.php';

$TC = new TransactionController();
$PC = new ProductsController();
$SC = new StoreController();

$error = '';
$success = '';

// Handle Transaction Creation Pipeline via POST Payload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_transaction') {
  $store_id = intval($_POST['store_id']);
  $payment_string = $_POST['payment_type'] ?? 'Cash';
  $raw_cart_data = $_POST['cart_data'] ?? '[]';

  $decoded_cart = json_decode($raw_cart_data, true);

  if (empty($store_id)) {
    $error = "Please select a store!";
  } elseif (empty($decoded_cart) || !is_array($decoded_cart)) {
    $error = "Cart is empty or contains malformed data!";
  } else {
    // Reconstruct into application-compatible item array format
    $processed_cart = [];
    $stock_valid = true;

    foreach ($decoded_cart as $item) {
      $p_id = intval($item['productId']);
      $qty = intval($item['quantity']);
      $product = $PC->findById($p_id);

      if (!$product) {
        $error = "Product ID {$p_id} could not be resolved.";
        $stock_valid = false;
        break;
      }

      if ($qty > $product->stock) {
        $error = "Insufficient stock remaining for product: {$product->productName}.";
        $stock_valid = false;
        break;
      }

      $processed_cart[] = [
        'product_id'   => $product->productId,
        'product_name' => $product->productName,
        'price'        => $product->price,
        'quantity'     => $qty
      ];
    }

    if ($stock_valid) {
      $payment_type = PaymentType::tryFrom($payment_string) ?? PaymentType::Cash;
      $transactionToSave = new Transaction($store_id, (int)$_SESSION['id_admin'], $payment_type);

      if ($TC->save($transactionToSave, $processed_cart)) {
        $success = "Transaction processed successfully!";
      } else {
        $error = "Database execution anomaly encountered during preservation.";
      }
    }
  }
}

$storesList = $SC->getAllStores();
$productsList = $PC->getAvailableProducts();

// Server-side calculations initialized to 0 defaults for view abstraction compliance
$subtotal = 0;
$total = 0;

include 'views/transactions_view.php';
