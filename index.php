<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'dashboard';
include './connect.php';

// Get statistics
$totalProducts = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM products"))['count'];
$totalCustomers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM customers"))['count'];
$totalTransactions = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM transactions"))['count'];
$totalSales = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(subtotal) as total FROM transaction_details"))['total'] ?? 0;

// Get recent transactions
$recentTrans = mysqli_query($conn, "
  SELECT t.transaction_id, c.customer_name, t.transaction_date, 
         SUM(td.subtotal) as total
  FROM transactions t
  JOIN customers c ON t.customer_id = c.customer_id
  JOIN transaction_details td ON t.transaction_id = td.transaction_id
  GROUP BY t.transaction_id
  ORDER BY t.transaction_date DESC
  LIMIT 5
");
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
          <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
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
        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-6 mb-8">
          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-blue-100 rounded-lg">
              <i class="fas fa-box text-blue-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Products</p>
              <p class="text-3xl font-bold text-gray-800"><?php echo $totalProducts; ?></p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-green-100 rounded-lg">
              <i class="fas fa-users text-green-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Customers</p>
              <p class="text-3xl font-bold text-gray-800"><?php echo $totalCustomers; ?></p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-orange-100 rounded-lg">
              <i class="fas fa-exchange-alt text-orange-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Transactions</p>
              <p class="text-3xl font-bold text-gray-800"><?php echo $totalTransactions; ?></p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-purple-100 rounded-lg">
              <i class="fas fa-money-bill-wave text-purple-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Sales</p>
              <p class="text-3xl font-bold text-gray-800">Rp <?php echo number_format($totalSales, 0, ',', '.'); ?></p>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="*:text-xl">
                  <th class="px-6 py-3 font-semibold text-gray-700">No</th>
                  <th class="px-6 py-3 font-semibold text-gray-700">Transaction ID</th>
                  <th class="px-6 py-3 font-semibold text-gray-700">Customer</th>
                  <th class="px-6 py-3 font-semibold text-gray-700">Date</th>
                  <th class="px-6 py-3 font-semibold text-gray-700">Total</th>
                  <th class="px-6 py-3 font-semibold text-gray-700">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($recentTrans)) {
                  $transId = str_pad($row['transaction_id'], 5, '0', STR_PAD_LEFT);
                  $date = date('d/m/Y H:i', strtotime($row['transaction_date']));
                ?>
                  <tr class="hover:bg-gray-50 *:text-lg">
                    <td class="px-6 py-4"><?php echo $no++; ?></td>
                    <td class="px-6 py-4">TRX-<?php echo $transId; ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td class="px-6 py-4"><?php echo $date; ?></td>
                    <td class="px-6 py-4 font-semibold">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                    <td class="px-6 py-4">
                      <button onclick="viewTransactionDetails(<?php echo $row['transaction_id']; ?>)" class="hover:bg-blue-700 p-6 py-1 text-white bg-blue-600 rounded">View</button>
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

  <!-- Transaction Details Modal -->
  <div id="transactionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-2/3 overflow-y-auto p-12">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-2xl font-bold text-gray-800">Transaction Details</h3>
        <button onclick="closeTransactionModal()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <div id="transactionContent">
        <div class="text-center text-gray-500 py-8">
          <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
          <p>Loading...</p>
        </div>
      </div>

      <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
        <button onclick="closeTransactionModal()" class="flex-1 px-4 py-2 font-semibold text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
          Close
        </button>
      </div>
    </div>
  </div>

  <script>
    function viewTransactionDetails(transactionId) {
      const modal = document.getElementById('transactionModal');
      const content = document.getElementById('transactionContent');
      modal.classList.remove('hidden');

      // Fetch transaction details
      fetch('get_transaction_details.php?transaction_id=' + transactionId)
        .then(response => response.text())
        .then(data => {
          content.innerHTML = data;
        })
        .catch(error => {
          content.innerHTML = '<div class="text-red-600 text-center py-8">Error loading transaction details</div>';
          console.error('Error:', error);
        });
    }

    function closeTransactionModal() {
      document.getElementById('transactionModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('transactionModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeTransactionModal();
      }
    });
  </script>
</body>

</html>