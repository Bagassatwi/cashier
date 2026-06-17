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
$totalstores = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count from store"))['count'];
$totalTransactions = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as count FROM transactions"))['count'];
$totalSales = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(subtotal) as total FROM transaction_details"))['total'] ?? 0;

// Get recent transactions
$recentTrans = mysqli_query($conn, "
  SELECT t.transaction_id, c.store_name, t.transaction_date, 
         SUM(td.subtotal) as total
  FROM transactions t
  JOIN store c ON t.store_id = c.store_id
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-linear-to-br from-blue-50 to-blue-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Products</h3>
                <div class="p-3 bg-blue-600 text-white rounded-lg">
                  <i class="fas fa-box text-lg"></i>
                </div>
              </div>
              <p class="text-4xl font-bold text-gray-800"><?php echo $totalProducts; ?></p>
              <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-check-circle text-green-600 mr-1"></i>Active items
              </p>
            </div>
          </div>

          <div class="bg-linear-to-br from-green-50 to-green-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total stores</h3>
                <div class="p-3 bg-green-600 text-white rounded-lg">
                  <i class="fas fa-users text-lg"></i>
                </div>
              </div>
              <p class="text-4xl font-bold text-gray-800"><?php echo $totalstores; ?></p>
              <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-user-plus text-green-600 mr-1"></i>Registered
              </p>
            </div>
          </div>

          <div class="bg-linear-to-br from-orange-50 to-orange-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Transactions</h3>
                <div class="p-3 bg-orange-600 text-white rounded-lg">
                  <i class="fas fa-exchange-alt text-lg"></i>
                </div>
              </div>
              <p class="text-4xl font-bold text-gray-800"><?php echo $totalTransactions; ?></p>
              <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-history text-orange-600 mr-1"></i>Completed
              </p>
            </div>
          </div>

          <div class="bg-linear-to-br from-purple-50 to-purple-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Sales</h3>
                <div class="p-3 bg-purple-600 text-white rounded-lg">
                  <i class="fas fa-money-bill-wave text-lg"></i>
                </div>
              </div>
              <p class="text-3xl font-bold text-gray-800">Rp <?php echo number_format($totalSales, 0, ',', '.'); ?></p>
              <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-chart-line text-purple-600 mr-1"></i>Revenue
              </p>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-linear-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
            <div class="flex items-center gap-3">
              <i class="fas fa-history text-white text-2xl"></i>
              <h3 class="text-xl font-bold text-white">Recent Transactions</h3>
              <span class="ml-auto text-gray-300 text-sm"><?php echo $totalTransactions; ?> total</span>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-100 border-b-2 border-gray-300">
                <tr>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">No</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">Transaction ID</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">store</th>
                  <th class="px-6 py-4 text-left font-bold text-gray-700 text-sm uppercase tracking-wide">Date & Time</th>
                  <th class="px-6 py-4 text-right font-bold text-gray-700 text-sm uppercase tracking-wide">Total</th>
                  <th class="px-6 py-4 text-center font-bold text-gray-700 text-sm uppercase tracking-wide">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($recentTrans)) {
                  $transId = str_pad($row['transaction_id'], 5, '0', STR_PAD_LEFT);
                  $date = date('d/m/Y H:i', strtotime($row['transaction_date']));
                  $bg_class = ($no % 2 == 0) ? 'bg-white' : 'bg-gray-50';
                ?>
                  <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $no++; ?></td>
                    <td class="px-6 py-4 font-bold text-blue-600">TRX-<?php echo $transId; ?></td>
                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($row['store_name']); ?></td>
                    <td class="px-6 py-4 text-gray-700"><?php echo $date; ?></td>
                    <td class="px-6 py-4 font-bold text-green-600 text-right">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                    <td class="px-6 py-4 text-center">
                      <button onclick="viewTransactionDetails(<?php echo $row['transaction_id']; ?>)" class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition font-semibold">
                        <i class="fas fa-eye"></i>View
                      </button>
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
    <div class="bg-white rounded-lg shadow-2xl w-9/12 2xl:w-7/12 overflow-y-auto">
      <div class="sticky top-0 bg-linear-to-r from-blue-600 to-blue-700 p-6 flex items-center justify-between border-b border-blue-800">
        <div class="flex items-center gap-3">
          <i class="fas fa-receipt text-white text-2xl"></i>
          <h3 class="text-2xl font-bold text-black">Transaction Details</h3>
        </div>
        <button onclick="closeTransactionModal()" class="text-black hover:text-gray-800 transition">
          <i class="fas fa-times text-2xl"></i>
        </button>
      </div>

      <div id="transactionContent" class="p-8">
        <div class="text-center text-gray-500 py-12">
          <i class="fas fa-spinner fa-spin text-4xl mb-3 text-blue-400"></i>
          <p class="text-lg">Loading transaction details...</p>
        </div>
      </div>

      <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50">
        <button onclick="closeTransactionModal()" class="flex-1 px-4 py-2.5 font-semibold text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200">
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