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
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Products</h3>
          <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="hover:bg-blue-700 flex items-center gap-2 px-6 py-2 text-white bg-blue-600 rounded-lg">
            <i class="fas fa-plus"></i> Add Product
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <form method="GET" class="relative">
            <input type="text" name="search" placeholder="Search product..." value="<?php echo htmlspecialchars($search); ?>" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="absolute right-3 top-2">
              <i class="fas fa-search text-gray-400"></i>
            </button>
          </form>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
          <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 font-semibold text-gray-700">No</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Product Name</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Price</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Stock</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php
              $no = 1;
              while ($row = mysqli_fetch_assoc($result)) {
              ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4"><?php echo $no++; ?></td>
                  <td class="px-6 py-4"><?php echo htmlspecialchars($row['product_name']); ?></td>
                  <td class="px-6 py-4">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                  <td class="px-6 py-4"><?php echo $row['stock']; ?></td>
                  <td class="flex gap-2 px-6 py-4">
                    <a href="?edit=<?php echo $row['product_id']; ?>" class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</a>
                    <a href="?delete=<?php echo $row['product_id']; ?>" onclick="return confirm('Are you sure?')" class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Add/Edit Modal -->
  <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800"><?php echo $edit_product ? 'Edit Product' : 'Add Product'; ?></h3>
        <button onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <?php if (isset($error)) { ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_product ? 'edit' : 'add'; ?>">
        <?php if ($edit_product) { ?>
          <input type="hidden" name="product_id" value="<?php echo $edit_product['product_id']; ?>">
        <?php } ?>

        <div class="mb-4">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
          <input type="text" name="product_name" value="<?php echo $edit_product ? htmlspecialchars($edit_product['product_name']) : ''; ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="mb-4">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Price</label>
          <input type="number" name="price" value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>" step="0.01" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="mb-6">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Stock</label>
          <input type="number" name="stock" value="<?php echo $edit_product ? $edit_product['stock'] : '0'; ?>" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="flex gap-3">
          <button type="submit" class="flex-1 px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <?php echo $edit_product ? 'Update' : 'Add'; ?>
          </button>
          <button type="button" onclick="document.getElementById('addModal').classList.add('hidden'); location.href='products.php'" class="flex-1 px-4 py-2 font-semibold text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    <?php if ($edit_product) { ?>
      document.getElementById('addModal').classList.remove('hidden');
    <?php } ?>
  </script>
</body>

</html>