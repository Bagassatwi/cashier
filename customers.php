<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'customers';
include './connect.php';

// Handle Delete
if (isset($_GET['delete'])) {
  $customer_id = intval($_GET['delete']);
  $delete_query = "DELETE FROM customers WHERE customer_id = ?";
  $stmt = $conn->prepare($delete_query);
  $stmt->bind_param("i", $customer_id);
  if ($stmt->execute()) {
    echo "<script>alert('Customer deleted successfully!');location.href='customers.php';</script>";
  } else {
    echo "<script>alert('Error deleting customer!');location.href='customers.php';</script>";
  }
  $stmt->close();
}

// Handle Add Customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
  $customer_name = htmlspecialchars($_POST['customer_name'] ?? '');
  $phone = htmlspecialchars($_POST['phone'] ?? '');

  if (empty($customer_name)) {
    $error = "Customer name is required!";
  } else {
    $insert_query = "INSERT INTO customers (customer_name, phone) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ss", $customer_name, $phone);
    if ($stmt->execute()) {
      echo "<script>alert('Customer added successfully!');location.href='customers.php';</script>";
      exit();
    } else {
      $error = "Error adding customer: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Handle Edit Customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
  $customer_id = intval($_POST['customer_id']);
  $customer_name = htmlspecialchars($_POST['customer_name'] ?? '');
  $phone = htmlspecialchars($_POST['phone'] ?? '');

  if (empty($customer_name)) {
    $error = "Customer name is required!";
  } else {
    $update_query = "UPDATE customers SET customer_name = ?, phone = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $customer_name, $phone, $customer_id);
    if ($stmt->execute()) {
      echo "<script>alert('Customer updated successfully!');location.href='customers.php';</script>";
      exit();
    } else {
      $error = "Error updating customer: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Get search query
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Get customers
if ($search) {
  $query = "SELECT * FROM customers WHERE customer_name LIKE ? OR phone LIKE ? ORDER BY customer_name ASC";
  $search_param = "%$search%";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $search_param, $search_param);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = mysqli_query($conn, "SELECT * FROM customers ORDER BY customer_name ASC");
}

// Check if editing
$edit_customer = null;
if (isset($_GET['edit'])) {
  $customer_id = intval($_GET['edit']);
  $edit_result = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $customer_id");
  $edit_customer = mysqli_fetch_assoc($edit_result);
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
          <h2 class="text-xl font-semibold text-gray-800">Customers</h2>
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
          <h3 class="text-2xl font-bold text-gray-800">Customers</h3>
          <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="hover:bg-blue-700 flex items-center gap-2 px-6 py-2 text-white bg-blue-600 rounded-lg">
            <i class="fas fa-plus"></i> Add Customer
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <form method="GET" class="relative">
            <input type="text" name="search" placeholder="Search customer..." value="<?php echo htmlspecialchars($search); ?>" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="absolute right-3 top-2">
              <i class="fas fa-search text-gray-400"></i>
            </button>
          </form>
        </div>

        <!-- Customers Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
          <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 font-semibold text-gray-700">No</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Customer Name</th>
                <th class="px-6 py-4 font-semibold text-gray-700">Phone</th>
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
                  <td class="px-6 py-4"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                  <td class="px-6 py-4"><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></td>
                  <td class="flex gap-2 px-6 py-4">
                    <a href="?edit=<?php echo $row['customer_id']; ?>" class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</a>
                    <a href="?delete=<?php echo $row['customer_id']; ?>" onclick="return confirm('Are you sure?')" class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</a>
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
        <h3 class="text-xl font-bold text-gray-800"><?php echo $edit_customer ? 'Edit Customer' : 'Add Customer'; ?></h3>
        <button onclick="document.getElementById('addModal').classList.add('hidden'); location.href='customers.php'" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <?php if (isset($error)) { ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <form method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_customer ? 'edit' : 'add'; ?>">
        <?php if ($edit_customer) { ?>
          <input type="hidden" name="customer_id" value="<?php echo $edit_customer['customer_id']; ?>">
        <?php } ?>

        <div class="mb-4">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Name</label>
          <input type="text" name="customer_name" value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['customer_name']) : ''; ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="mb-6">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
          <input type="text" name="phone" value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['phone'] ?? '') : ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="flex gap-3">
          <button type="submit" class="flex-1 px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <?php echo $edit_customer ? 'Update' : 'Add'; ?>
          </button>
          <button type="button" onclick="document.getElementById('addModal').classList.add('hidden'); location.href='customers.php'" class="flex-1 px-4 py-2 font-semibold text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    <?php if ($edit_customer) { ?>
      document.getElementById('addModal').classList.remove('hidden');
    <?php } ?>
  </script>
</body>

</html>