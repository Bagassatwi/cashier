<!DOCTYPE html>
<html lang="en">
<?php
/** @var array<string|int, array{store_id: int, store_name: string}> $storesList 
 * @var array<string|int, array{product_id: int, product_name: string, price: float, stock: int}> $productsList 
 * @var int $total */
?>
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
        <?php if (!empty($error)) { ?>
          <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
          </div>
        <?php } ?>
        <?php if (!empty($success)) { ?>
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
                  <input type="hidden" name="cart_data" id="cart_data">
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
                            <option value="<?php echo (int)$product['product_id']; ?>" data-price="<?php echo (float)$product['price']; ?>" data-stock="<?php echo (int)$product['stock']; ?>" data-name="<?php echo htmlspecialchars($product['product_name']); ?>">
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
                    </div>
                  </div>

                  <div class="flex gap-4">
                    <button type="button" onclick="clearCart()" class="flex-1 px-4 py-3 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg text-center transition">
                      <i class="fas fa-redo mr-2"></i>Reset
                    </button>
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
                  <i class="fas fa-calculator text-black text-2xl"></i>
                  <h4 class="text-xl font-bold text-black">Summary</h4>
                </div>
              </div>
              <div class="p-6">
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-600">
                  <p class="text-sm text-gray-600 font-semibold">Items in Cart</p>
                  <p class="text-3xl font-bold text-blue-600" id="summaryCount">0</p>
                </div>
                <div class="space-y-4 mb-8">
                  <div class="flex justify-between text-gray-700">
                    <span class="font-semibold">Subtotal:</span>
                    <span class="font-semibold" id="summarySubtotal">Rp 0</span>
                  </div>
                  <div class="pt-4 border-t-2 border-gray-200">
                    <div class="flex justify-between">
                      <span class="font-bold text-gray-800">Total Amount:</span>
                      <span class="text-3xl font-bold text-green-600" id="summaryTotal">Rp 0</span>
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
  let items = [];
  const itemsInCartContainer = document.getElementById('cartItems');

  const parseCart = () => {
    const data = localStorage.getItem('cart');
    try {
      return data ? JSON.parse(data) : [];
    } catch (e) {
      return [];
    }
  };

  const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      maximumFractionDigits: 0
    }).format(value);
  };

  const renderUI = () => {
    itemsInCartContainer.textContent = '';

    if (items.length === 0) {
      const emptyContainer = document.createElement('div');
      emptyContainer.className = 'text-center py-8 text-gray-500';
      emptyContainer.innerHTML = '<i class="fas fa-inbox text-4xl mb-2 opacity-50"></i><p>No items in cart yet</p>';
      itemsInCartContainer.appendChild(emptyContainer);

      document.getElementById('summaryCount').textContent = '0';
      document.getElementById('summarySubtotal').textContent = formatCurrency(0);
      document.getElementById('summaryTotal').textContent = formatCurrency(0);
      return;
    }

    const fragment = document.createDocumentFragment();
    let computedSubtotal = 0;

    items.forEach((element, index) => {
      const itemSubtotal = parseFloat(element.price) * parseInt(element.quantity);
      computedSubtotal += itemSubtotal;

      const row = document.createElement('div');
      row.className = 'flex items-center justify-between p-4 bg-linear-to-r from-gray-50 to-blue-50 rounded-lg border border-gray-200 hover:border-blue-400 transition';

      row.innerHTML = `
        <div class="flex-1">
          <p class="font-bold text-gray-800">${element.name}</p>
          <p class="text-sm text-gray-600">
            Qty: <span class="font-semibold">${element.quantity}</span> × <span class="font-semibold">${formatCurrency(element.price)}</span>
          </p>
        </div>
        <div class="flex items-center gap-6">
          <div class="text-right">
            <p class="text-xs text-gray-600">Subtotal</p>
            <p class="text-xl font-bold text-green-600">${formatCurrency(itemSubtotal)}</p>
          </div>
          <button type="button" onclick="removeItem(${index})" class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            <i class="fas fa-trash-alt text-sm"></i>
          </button>
        </div>
      `;
      fragment.appendChild(row);
    });

    itemsInCartContainer.appendChild(fragment);
    document.getElementById('summaryCount').textContent = items.length.toString();
    document.getElementById('summarySubtotal').textContent = formatCurrency(computedSubtotal);
    document.getElementById('summaryTotal').textContent = formatCurrency(computedSubtotal);
  };

  const addToCart = () => {
    const productSelect = document.getElementById('product_select');
    const productId = productSelect.value;
    const quantityInput = document.getElementById('quantity_input');
    const quantity = parseInt(quantityInput.value);

    if (!productId) {
      alert('Please select a product!');
      return;
    }
    if (isNaN(quantity) || quantity <= 0) {
      alert('Please enter a valid quantity!');
      return;
    }

    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const name = selectedOption.getAttribute('data-name');
    const price = parseFloat(selectedOption.getAttribute('data-price'));
    const stock = parseInt(selectedOption.getAttribute('data-stock'));

    const existingIndex = items.findIndex(item => item.productId === productId);
    let currentQtyInCart = existingIndex !== -1 ? items[existingIndex].quantity : 0;

    if ((currentQtyInCart + quantity) > stock) {
      alert(`Insufficient stock! Available stock: ${stock}`);
      return;
    }

    if (existingIndex !== -1) {
      items[existingIndex].quantity += quantity;
    } else {
      items.push({
        name,
        price,
        productId,
        quantity
      });
    }

    localStorage.setItem('cart', JSON.stringify(items));
    renderUI();
    productSelect.value = '';
  };

  const removeItem = (index) => {
    items.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(items));
    renderUI();
  };

  const clearCart = () => {
    items = [];
    localStorage.removeItem('cart');
    renderUI();
  };

  function saveTrans() {
    const storeId = document.getElementById('store_id').value;
    if (!storeId) {
      alert('Please select a store!');
      return false;
    }
    if (items.length === 0) {
      alert('Your cart is empty!');
      return false;
    }
    document.getElementById('cart_data').value = JSON.stringify(items);
    localStorage.removeItem('cart'); // Clear storage upon validation pass
    return true;
  }

  items = parseCart();
  renderUI();
</script>