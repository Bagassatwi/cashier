<!DOCTYPE html>
<html lang="en">
<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
    <main class="flex-1">
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

      <div class="p-8">
        <?php if ($error) { ?>
          <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
        <?php } ?>
        <?php if ($success) { ?>
          <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-600 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success); ?>
          </div>
        <?php } ?>

        <div class="mb-8">
          <h3 class="text-3xl font-bold text-gray-800">Create New Transaction</h3>
          <p class="text-gray-600 text-sm mt-1">Select products and complete the transaction</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <div class="bg-linear-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
                <div class="flex items-center gap-3">
                  <i class="fas fa-shopping-cart text-black text-2xl"></i>
                  <h3 class="text-xl font-bold text-black">Transaction Details</h3>
                </div>
              </div>

              <div class="p-8">
                <form method="POST" id="transactionForm">
                  <div class="mb-8">
                    <label class="block mb-3 text-sm font-bold text-gray-700 uppercase tracking-wide">
                      <i class="fas fa-user text-blue-600 mr-2"></i>Select store
                    </label>
                    <select name="store_id" id="store_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" required>
                      <option value="">-- Choose a store --</option>
                      <?php foreach ($storesList as $store) { ?>
                        <option value="<?php echo (int)$store['store_id']; ?>"><?php echo htmlspecialchars($store['store_name']); ?></option>
                      <?php } ?>
                    </select>
                  </div>

                  <div class="mb-8 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                    <h4 class="mb-6 font-bold text-lg text-gray-800 flex items-center gap-2">
                      <i class="fas fa-plus-circle text-blue-600"></i>Add Product to Cart
                    </h4>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                      <div class="col-span-2">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Product</label>
                        <select id="product_select" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition">
                          <option value="">Select a product...</option>
                          <?php foreach ($productsList as $product) { ?>
                            <option value="<?php echo (int)$product['product_id']; ?>" data-price="<?php echo (float)$product['price']; ?>" data-stock="<?php echo (int)$product['stock']; ?>">
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

                  <div class="mb-8">
                    <h4 class="mb-4 font-bold text-lg text-gray-800 flex items-center gap-2">
                      <i class="fas fa-list text-gray-600"></i>Items in Cart
                    </h4>
                    <div class="space-y-3 max-h-80 overflow-y-auto" id="cartItems">
                      <?php if (count($_SESSION['cart']) === 0) { ?>
                        <div class="text-center py-8 text-gray-500">
                          <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                          <p>No items in cart yet</p>
                        </div>
                      <?php } else { ?>
                        <?php foreach ($_SESSION['cart'] as $item) { ?>
                          <div class="flex items-center justify-between p-4 bg-linear-to-r from-gray-50 to-blue-50 rounded-lg border border-gray-200 hover:border-blue-400 transition">
                            <div class="flex-1">
                              <p class="font-bold text-gray-800"><?php echo htmlspecialchars($item['product_name']); ?></p>
                              <p class="text-sm text-gray-600">
                                Qty: <span class="font-semibold"><?php echo (int)$item['quantity']; ?></span> ×
                                Rp <span class="font-semibold"><?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                              </p>
                            </div>
                            <div class="flex items-center gap-6">
                              <div class="text-right">
                                <p class="text-xs text-gray-600">Subtotal</p>
                                <p class="text-xl font-bold text-green-600">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></p>
                              </div>
                              <a href="?remove_cart=<?php echo (int)$item['product_id']; ?>" class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                <i class="fas fa-trash-alt text-sm"></i>
                              </a>
                            </div>
                          </div>
                        <?php } ?>
                      <?php } ?>
                    </div>
                  </div>

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

          <div>
            <div class="sticky top-8 bg-white rounded-lg shadow-md overflow-hidden">
              <div class="bg-linear-to-r from-blue-600 to-blue-700 p-6">
                <div class="flex items-center gap-3">
                  <i class="fas fa-calculator text-white text-2xl"></i>
                  <h4 class="text-xl font-bold text-white">Summary</h4>
                </div>
              </div>
              <div class="p-6">
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-600">
                  <p class="text-sm text-gray-600 font-semibold">Items in Cart</p>
                  <p class="text-3xl font-bold text-blue-600"><?php echo count($_SESSION['cart']); ?></p>
                </div>
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
    const storeId = document.getElementById('store_id').value;
    if (!storeId) {
      alert('Please select a store!');
      return false;
    }
    return true;
  }
</script>