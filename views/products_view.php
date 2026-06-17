<!DOCTYPE html>
<html lang="en">
<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
    <main class="flex-1">
      <header class="bg-white shadow">
        <div class="flex items-center justify-between px-8 py-4">
          <h2 class="text-xl font-semibold text-gray-800">Products</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?></span>
            <button class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
              <i class="fas fa-user"></i>
            </button>
          </div>
        </div>
      </header>

      <div class="p-8">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-3xl font-bold text-gray-800">Product Inventory</h3>
            <p class="text-gray-600 text-sm mt-1">Manage your product catalog and stock</p>
          </div>
          <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition"><i class="fas fa-plus-circle"></i> Add New Product</button>
        </div>

        <div class="mb-6">
          <form method="GET" class="relative">
            <i class="fas fa-search absolute left-4 top-1/3 text-gray-400"></i>
            <input type="search" name="search" placeholder="Search by product name..." value="<?php echo htmlspecialchars($search); ?>" class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
          </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">No</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">Product Name</th>
                  <th class="px-6 py-4 text-center font-bold text-gray-700 text-sm uppercase tracking-wide">Price</th>
                  <th class="px-6 py-4 text-center font-bold text-gray-700 text-sm uppercase tracking-wide">Stock</th>
                  <th class="px-6 py-4 text-center font-bold text-gray-700 text-sm uppercase tracking-wide">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php
                $no = 1;
                foreach ($productsList as $row) {
                  $bg_class = ($no % 2 === 0) ? 'bg-white' : 'bg-gray-50';
                  $stock_status = $row['stock'] > 10 ? 'bg-green-100 text-green-800' : ($row['stock'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                ?>
                  <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $no++; ?></td>
                    <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td class="px-6 py-4 text-center font-bold text-green-600">Rp <?php echo number_format((int)$row['price'], 0, ',', '.'); ?></td>
                    <td class="px-6 py-4 text-center">
                      <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?php echo $stock_status; ?>"><?php echo (int)$row['stock']; ?> units</span>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex gap-2 justify-center">
                        <a href="?edit=<?php echo (int)$row['product_id']; ?>" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded transition"><i class="fas fa-edit"></i>Edit</a>
                        <a href="?delete=<?php echo (int)$row['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded transition"><i class="fas fa-trash"></i>Delete</a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>

  <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-2xl w-11/12 md:w-96">
      <div class="bg-linear-to-r from-blue-600 to-blue-700 p-6 flex items-center justify-between border-b border-blue-800">
        <div class="flex items-center gap-3">
          <i class="fas fa-<?php echo $edit_product ? 'edit' : 'box-open'; ?> text-black text-2xl"></i>
          <h3 class="text-xl font-bold text-black"><?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></h3>
        </div>
        <button onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="text-black hover:text-gray-700 transition"><i class="fas fa-times text-2xl"></i></button>
      </div>
      <div class="p-8">
        <?php if ($error !== null) { ?>
          <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded"><i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
        <form method="POST">
          <input type="hidden" name="action" value="<?php echo $edit_product ? 'edit' : 'add'; ?>">
          <?php if ($edit_product) { ?>
            <input type="hidden" name="product_id" value="<?php echo (int)$edit_product['product_id']; ?>">
          <?php } ?>
          <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-cube text-blue-600 mr-2"></i>Product Name</label>
            <input type="text" name="product_name" value="<?php echo $edit_product ? htmlspecialchars($edit_product['product_name']) : ''; ?>" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter product name">
          </div>
          <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-tag text-blue-600 mr-2"></i>Price (Rp)</label>
            <input type="number" name="price" value="<?php echo $edit_product ? (float)$edit_product['price'] : ''; ?>" step="0.01" min="0" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter price">
          </div>
          <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2"><i class="fas fa-warehouse text-blue-600 mr-2"></i>Stock</label>
            <input type="number" name="stock" value="<?php echo $edit_product ? (int)$edit_product['stock'] : '0'; ?>" min="0" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter stock quantity">
          </div>
          <div class="flex gap-3">
            <button type="submit" class="flex-1 px-4 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"><i class="fas fa-check-circle mr-2"></i><?php echo $edit_product ? 'Update' : 'Add'; ?></button>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="flex-1 px-4 py-3 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg transition">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    <?php if ($edit_product !== null) { ?>
      document.getElementById('addModal').classList.remove('hidden');
    <?php } ?>
  </script>
</body>

</html>