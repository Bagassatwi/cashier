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
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-3xl font-bold text-gray-800">Customer Management</h3>
            <p class="text-gray-600 text-sm mt-1">Manage all your customers in one place</p>
          </div>
          <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">
            <i class="fas fa-plus-circle"></i> Add New Customer
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <form method="GET" class="relative">
            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
            <input type="text" name="search" placeholder="Search by name or phone..." value="<?php echo htmlspecialchars($search); ?>" class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
          </form>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
            <div class="flex items-center gap-3">
              <i class="fas fa-users text-white text-2xl"></i>
              <h3 class="text-xl font-bold text-white">All Customers</h3>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">No</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">Customer Name</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">Phone</th>
                  <th class="px-6 py-4 text-center font-bold text-gray-700 text-sm uppercase tracking-wide">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                  $bg_class = ($no % 2 == 0) ? 'bg-white' : 'bg-gray-50';
                ?>
                  <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $no++; ?></td>
                    <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($row['phone'] ?? '-'); ?></td>
                    <td class="px-6 py-4">
                      <div class="flex gap-2 justify-center">
                        <a href="?edit=<?php echo $row['customer_id']; ?>" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded transition">
                          <i class="fas fa-edit"></i>Edit
                        </a>
                        <a href="?delete=<?php echo $row['customer_id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?')" class="inline-flex items-center gap-1 px-3 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded transition">
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
          <i class="fas fa-<?php echo $edit_customer ? 'edit' : 'user-plus'; ?> text-white text-2xl"></i>
          <h3 class="text-xl font-bold text-white"><?php echo $edit_customer ? 'Edit Customer' : 'Add New Customer'; ?></h3>
        </div>
        <button onclick="document.getElementById('addModal').classList.add('hidden'); location.href='customers.php'" class="text-blue-100 hover:text-white transition">
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
          <input type="hidden" name="action" value="<?php echo $edit_customer ? 'edit' : 'add'; ?>">
          <?php if ($edit_customer) { ?>
            <input type="hidden" name="customer_id" value="<?php echo $edit_customer['customer_id']; ?>">
          <?php } ?>

          <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              <i class="fas fa-user text-blue-600 mr-2"></i>Customer Name
            </label>
            <input type="text" name="customer_name" value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['customer_name']) : ''; ?>" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter full name">
          </div>

          <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">
              <i class="fas fa-phone text-blue-600 mr-2"></i>Phone Number
            </label>
            <input type="text" name="phone" value="<?php echo $edit_customer ? htmlspecialchars($edit_customer['phone'] ?? '') : ''; ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium" placeholder="Enter phone number">
          </div>

          <div class="flex gap-3">
            <button type="submit" class="flex-1 px-4 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
              <i class="fas fa-check-circle mr-2"></i><?php echo $edit_customer ? 'Update' : 'Add'; ?>
            </button>
            <button type="button" onclick="document.getElementById('addModal').classList.add('hidden'); location.href='customers.php'" class="flex-1 px-4 py-3 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg transition">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    <?php if ($edit_customer) { ?>
      document.getElementById('addModal').classList.remove('hidden');
    <?php } ?>
  </script>
</body>

</html>