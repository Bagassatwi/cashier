<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'transactions';
include './connect.php';

// Initialize cart in session
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

$error = '';
$success = '';

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']);

  // Get product details
  $product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
  $product = mysqli_fetch_assoc($product_query);

  if (!$product) {
    $error = "Product not found!";
  } elseif ($quantity > $product['stock']) {
    $error = "Insufficient stock!";
  } else {
    // Check if already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['product_id'] == $product_id) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
      }
    }

    if (!$found) {
      $_SESSION['cart'][] = array(
        'product_id' => $product_id,
        'product_name' => $product['product_name'],
        'price' => $product['price'],
        'quantity' => $quantity
      );
    }
    echo "<script>location.href='transactions.php';</script>";
    exit();
  }
}

// Handle Remove from Cart
if (isset($_GET['remove_cart'])) {
  $product_id = intval($_GET['remove_cart']);
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['product_id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      break;
    }
  }
  echo "<script>location.href='transactions.php';</script>";
  exit();
}

// Handle Save Transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'save_transaction') {
  $customer_id = intval($_POST['customer_id']);
  $payment_type = htmlspecialchars($_POST['payment_type'] ?? 'Cash');

  if (empty($customer_id)) {
    $error = "Please select a customer!";
  } elseif (count($_SESSION['cart']) == 0) {
    $error = "Cart is empty!";
  } else {
    // Start transaction
    $conn->begin_transaction();
    try {
      $admin_id = $_SESSION['id_admin'];

      // Insert transaction
      $insert_trans = "INSERT INTO transactions (customer_id, admin_id, transaction_date) VALUES (?, ?, NOW())";
      $stmt = $conn->prepare($insert_trans);
      $stmt->bind_param("ii", $customer_id, $admin_id);
      $stmt->execute();
      $transaction_id = $conn->insert_id;
      $stmt->close();

      // Insert transaction details
      foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $insert_detail = "INSERT INTO transaction_details (transaction_id, product_id, quantity, subtotal, payment_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_detail);
        $stmt->bind_param("iiiis", $transaction_id, $item['product_id'], $item['quantity'], $subtotal, $payment_type);
        $stmt->execute();

        // Update product stock
        $update_stock = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
        $stmt2 = $conn->prepare($update_stock);
        $stmt2->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt2->execute();
        $stmt2->close();
        $stmt->close();
      }

      $conn->commit();

      // Clear cart
      $_SESSION['cart'] = array();

      echo "<script>alert('Transaction saved successfully! (ID: TRX-" . str_pad($transaction_id, 5, '0', STR_PAD_LEFT) . ")');location.href='transactions.php';</script>";
      exit();
    } catch (Exception $e) {
      $conn->rollback();
      $error = "Error saving transaction: " . $e->getMessage();
    }
  }
}

// Get customers for dropdown
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY customer_name ASC");

// Get products for dropdown
$products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0 ORDER BY product_name ASC");

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal;
?>
<!DOCTYPE html>
<html lang="en">

<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Top Bar -->
      <header class="bg-white shadow">
        <div class="flex items-center justify-between px-8 py-4">
          <h2 class="text-xl font-semibold text-gray-800">New Transaction</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
            <button class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
              <i class="fas fa-user"></i>
            </button>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="p-8">
        <?php if ($error) { ?>
          <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
          </div>
        <?php } ?>
        <?php if ($success) { ?>
          <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-600 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
          </div>
        <?php } ?>

        <div class="mb-8">
          <h3 class="text-3xl font-bold text-gray-800">Create New Transaction</h3>
          <p class="text-gray-600 text-sm mt-1">Select products and complete the transaction</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Form Section -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <!-- Header -->
              <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
                <div class="flex items-center gap-3">
                  <i class="fas fa-shopping-cart text-white text-2xl"></i>
                  <h3 class="text-xl font-bold text-white">Transaction Details</h3>
                </div>
              </div>

              <div class="p-8">
                <form method="POST" id="transactionForm">
                  <!-- Customer Selection -->
                  <div class="mb-8">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wide">
                      <i class="fas fa-user text-blue-600 mr-2"></i>Select Customer
                    </label>
                    <select name="customer_id" id="customer_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" required>
                      <option value="">-- Choose a customer --</option>
                      <?php while ($customer = mysqli_fetch_assoc($customers)) { ?>
                        <option value="<?php echo $customer['customer_id']; ?>"><?php echo htmlspecialchars($customer['customer_name']); ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <!-- Add Product Section -->
                  <div class="mb-8 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                    <h4 class="mb-6 font-bold text-lg text-gray-800 flex items-center gap-2">
                      <i class="fas fa-plus-circle text-blue-600"></i>Add Product to Cart
                    </h4>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                      <div class="col-span-2">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Product</label>
                        <select id="product_select" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition">
                          <option value="">Select a product...</option>
                          <?php
                          mysqli_data_seek($products, 0);
                          while ($product = mysqli_fetch_assoc($products)) {
                          ?>
                            <option value="<?php echo $product['product_id']; ?>" data-price="<?php echo $product['price']; ?>" data-stock="<?php echo $product['stock']; ?>">
                              <?php echo htmlspecialchars($product['product_name']) . ' (Rp ' . number_format($product['price'], 0, ',', '.') . ')'; ?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>

                      <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Qty</label>
                        <input type="number" id="quantity_input" value="1" min="1" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium">
                      </div>
                    </div>

                    <button type="button" onclick="addToCart()" class="w-full px-4 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center justify-center gap-2">
                      <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                  </div>

                  <!-- Cart Items -->
                  <div class="mb-8">
                    <h4 class="mb-4 font-bold text-lg text-gray-800 flex items-center gap-2">
                      <i class="fas fa-list text-gray-600"></i>Items in Cart
                    </h4>
                    <div class="space-y-3 max-h-80 overflow-y-auto" id="cartItems">
                      <?php if (count($_SESSION['cart']) == 0) { ?>
                        <div class="text-center py-8 text-gray-500">
                          <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                          <p>No items in cart yet</p>
                        </div>
                      <?php } else { ?>
                        <?php foreach ($_SESSION['cart'] as $item) { ?>
                          <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg border border-gray-200 hover:border-blue-400 transition">
                            <div class="flex-1">
                              <p class="font-bold text-gray-800"><?php echo htmlspecialchars($item['product_name']); ?></p>
                              <p class="text-sm text-gray-600">
                                Qty: <span class="font-semibold"><?php echo $item['quantity']; ?></span> ×
                                Rp <span class="font-semibold"><?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                              </p>
                            </div>
                            <div class="flex items-center gap-6">
                              <div class="text-right">
                                <p class="text-xs text-gray-600">Subtotal</p>
                                <p class="text-xl font-bold text-green-600">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                              </div>
                              <a href="?remove_cart=<?php echo $item['product_id']; ?>" class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                <i class="fas fa-trash-alt text-sm"></i>
                              </a>
                            </div>
                          </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex gap-4">
                    <a href="transactions.php" class="flex-1 px-4 py-3 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg text-center transition">
                      <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                    <button type="submit" class="flex-1 px-4 py-3 font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition" onclick="return saveTrans()">
                      <i class="fas fa-check-circle mr-2"></i>Save Transaction
                    </button>
                  </div>
                  <input type="hidden" name="action" value="save_transaction">
                </form>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div>
            <div class="sticky top-8 bg-white rounded-lg shadow-md overflow-hidden">
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
                <div class="flex items-center gap-3">
                  <i class="fas fa-calculator text-white text-2xl"></i>
                  <h4 class="text-xl font-bold text-white">Summary</h4>
                </div>
              </div>

              <div class="p-6">
                <!-- Items Count -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-600">
                  <p class="text-sm text-gray-600 font-semibold">Items in Cart</p>
                  <p class="text-3xl font-bold text-blue-600"><?php echo count($_SESSION['cart']); ?></p>
                </div>

                <!-- Totals -->
                <div class="space-y-4 mb-8">
                  <div class="flex justify-between text-gray-700">
                    <span class="font-semibold">Subtotal:</span>
                    <span class="font-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                  </div>
                  <div class="pt-4 border-t-2 border-gray-200">
                    <div class="flex justify-between">
                      <span class="font-bold text-gray-800">Total Amount:</span>
                      <span class="text-3xl font-bold text-green-600">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>
                  </div>
                </div>

                <!-- Payment Method -->
                <div class="p-4 bg-gray-100 rounded-lg">
                  <label class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-wide">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>Payment Method
                  </label>
                  <select name="payment_type" form="transactionForm" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium">
                    <option value="Cash">💵 Cash</option>
                    <option value="Card">💳 Card</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>

<script>
  function addToCart() {
    const productSelect = document.getElementById('product_select');
    const quantityInput = document.getElementById('quantity_input');
    const productId = productSelect.value;
    const quantity = parseInt(quantityInput.value);

    if (!productId) {
      alert('Please select a product!');
      return;
    }

    if (quantity <= 0) {
      alert('Please enter a valid quantity!');
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = '<input type="hidden" name="action" value="add_to_cart">' +
      '<input type="hidden" name="product_id" value="' + productId + '">' +
      '<input type="hidden" name="quantity" value="' + quantity + '">';
    document.body.appendChild(form);
    form.submit();
  }

  function saveTrans() {
    const customerId = document.getElementById('customer_id').value;
    if (!customerId) {
      alert('Please select a customer!');
      return false;
    }
    return true;
  }
</script>