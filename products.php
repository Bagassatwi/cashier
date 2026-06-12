<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'products';
include './connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
  $product_id = intval($_GET['delete']);
  $delete_query = "DELETE FROM products WHERE product_id = ?";
  $stmt = $conn->prepare($delete_query);
  $stmt->bind_param("i", $product_id);
  if ($stmt->execute()) {
    echo "<script>alert('Product deleted successfully!');location.href='products.php';</script>";
  } else {
    echo "<script>alert('Error deleting product!');location.href='products.php';</script>";
  }
  $stmt->close();
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
  $product_name = htmlspecialchars($_POST['product_name'] ?? '');
  $price = floatval($_POST['price'] ?? 0);
  $stock = intval($_POST['stock'] ?? 0);

  if (empty($product_name)) {
    $error = "Product name is required!";
  } elseif ($price <= 0) {
    $error = "Price must be greater than 0!";
  } else {
    $insert_query = "INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sdi", $product_name, $price, $stock);
    if ($stmt->execute()) {
      echo "<script>alert('Product added successfully!');location.href='products.php';</script>";
      exit();
    } else {
      $error = "Error adding product: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
  $product_id = intval($_POST['product_id']);
  $product_name = htmlspecialchars($_POST['product_name'] ?? '');
  $price = floatval($_POST['price'] ?? 0);
  $stock = intval($_POST['stock'] ?? 0);

  if (empty($product_name)) {
    $error = "Product name is required!";
  } elseif ($price <= 0) {
    $error = "Price must be greater than 0!";
  } else {
    $update_query = "UPDATE products SET product_name = ?, price = ?, stock = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sdii", $product_name, $price, $stock, $product_id);
    if ($stmt->execute()) {
      echo "<script>alert('Product updated successfully!');location.href='products.php';</script>";
      exit();
    } else {
      $error = "Error updating product: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Get search query
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Get products
if ($search) {
  $query = "SELECT * FROM products WHERE product_name LIKE ? ORDER BY product_name ASC";
  $search_param = "%$search%";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $search_param);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = mysqli_query($conn, "SELECT * FROM products ORDER BY product_name ASC");
}

// Check if editing
$edit_product = null;
if (isset($_GET['edit'])) {
  $product_id = intval($_GET['edit']);
  $edit_result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
  $edit_product = mysqli_fetch_assoc($edit_result);
}
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
          <h2 class="text-xl font-semibold text-gray-800">Products</h2>
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
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-3xl font-bold text-gray-800">Product Inventory</h3>
            <p class="text-gray-600 text-sm mt-1">Manage your product catalog and stock</p>
          </div>
          <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">
            <i class="fas fa-plus-circle"></i> Add New Product
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <form method="GET" class="relative">
            <i class="fas fa-search absolute left-4 top-1/3 text-gray-400"></i>
            <input type="search" name="search" placeholder="Search by product name..." value="<?php echo htmlspecialchars($search); ?>" class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
          </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
            <div class="flex items-center gap-3">
              <i class="fas fa-boxes text-white text-2xl"></i>
              <h3 class="text-xl font-bold text-white">All Products</h3>
            </div>
          </div>

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
                while ($row = mysqli_fetch_assoc($result)) {
                  $bg_class = ($no % 2 == 0) ? 'bg-white' : 'bg-gray-50';
                  $stock_status = $row['stock'] > 10 ? 'bg-green-100 text-green-800' : ($row['stock'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                ?>
                  <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $no++; ?></td>
                    <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td class="px-6 py-4 text-center font-bold text-green-600">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                    <td class="px-6 py-4 text-center">
                      <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?php echo $stock_status; ?>">
                        <?php echo $row['stock']; ?> units
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex gap-2 justify-center">
                        <a href="?edit=<?php echo $row['product_id']; ?>" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded transition">
                          <i class="fas fa-edit"></i>Edit
                        </a>
                        <a href="?delete=<?php echo $row['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded transition">
                          <i class="fas fa-trash"></i>Delete
                        </a>
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

  <!-- Add/Edit Modal -->
  <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-2xl w-11/12 md:w-96">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 flex items-center justify-between border-b border-blue-800">
        <div class="flex items-center gap-3">
          <i class="fas fa-<?php echo $edit_product ? 'edit' : 'box-open'; ?> text-white text-2xl"></i>
          <h3 class="text-xl font-bold text-white"><?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></h3>
        </div>
        <button onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="text-blue-100 hover:text-white transition">
          <i class="fas fa-times text-2xl"></i>
        </button>
      </div>

      <div class="p-8">
        <?php if (isset($error)) { ?>
          <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
          </div>
        <?php } ?>

        <form method="POST">
          <input type="hidden" name="action" value="<?php echo $edit_product ? 'edit' : 'add'; ?>">
          <?php if ($edit_product) { ?>
            <input type="hidden" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
          <?php } ?>

          <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              <i class="fas fa-cube text-blue-600 mr-2"></i>Product Name
            </label>
            <input type="text" name="product_name" value="<?php echo $edit_product ? htmlspecialchars($edit_product['product_name']) : ''; ?>" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter product name">
          </div>

          <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              <i class="fas fa-tag text-blue-600 mr-2"></i>Price (Rp)
            </label>
            <input type="number" name="price" value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>" step="0.01" min="0" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter price">
          </div>

          <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              <i class="fas fa-warehouse text-blue-600 mr-2"></i>Stock
            </label>
            <input type="number" name="stock" value="<?php echo $edit_product ? $edit_product['stock'] : '0'; ?>" min="0" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter stock quantity">
          </div>

          <div class="flex gap-3">
            <button type="submit" class="flex-1 px-4 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
              <i class="fas fa-check-circle mr-2"></i><?php echo $edit_product ? 'Update' : 'Add'; ?>
            </button>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="flex-1 px-4 py-3 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg transition">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    <?php if ($edit_product) { ?>
      document.getElementById('addModal').classList.remove('hidden');
    <?php } ?>
  </script>
</body>

</html>