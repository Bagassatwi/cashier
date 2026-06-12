<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'reports';
include './connect.php';

// Get filter dates
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

// Build query
$query = "
  SELECT t.transaction_id, c.customer_name, t.transaction_date, 
         SUM(td.subtotal) as total
  FROM transactions t
  JOIN customers c ON t.customer_id = c.customer_id
  JOIN transaction_details td ON t.transaction_id = td.transaction_id
  WHERE DATE(t.transaction_date) >= ? AND DATE(t.transaction_date) <= ?
  GROUP BY t.transaction_id
  ORDER BY t.transaction_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $from_date, $to_date);
$stmt->execute();
$result = $stmt->get_result();

// Calculate statistics
$stats_query = "
  SELECT 
    COUNT(DISTINCT t.transaction_id) as total_transactions,
    SUM(td.subtotal) as total_sales
  FROM transactions t
  JOIN transaction_details td ON t.transaction_id = td.transaction_id
  WHERE DATE(t.transaction_date) >= ? AND DATE(t.transaction_date) <= ?
";

$stats_stmt = $conn->prepare($stats_query);
$stats_stmt->bind_param("ss", $from_date, $to_date);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();

$total_transactions = $stats['total_transactions'] ?? 0;
$total_sales = $stats['total_sales'] ?? 0;
$average_transaction = $total_transactions > 0 ? $total_sales / $total_transactions : 0;
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
          <h2 class="text-xl font-semibold text-gray-800">Transaction History</h2>
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
        <!-- Filters -->
        <form method="GET" class="grid grid-cols-5 gap-4 mb-6">
          <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">From Date</label>
            <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
          </div>
          <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700">To Date</label>
            <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
          </div>
          <div class="flex items-end gap-2">
            <button type="submit" class="hover:bg-blue-700 flex-1 px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg">Filter</button>
            <a href="reports.php" class="hover:bg-gray-700 flex-1 px-4 py-2 font-semibold text-white bg-gray-600 rounded-lg text-center">Reset</a>
          </div>
        </form>

        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-6 mb-8">
          <div class="p-6 bg-white rounded-lg shadow">
            <div class="mb-2 text-sm text-gray-600">Total Transactions</div>
            <div class="text-4xl font-bold text-gray-800"><?php echo $total_transactions; ?></div>
          </div>
          <div class="p-6 bg-white rounded-lg shadow">
            <div class="mb-2 text-sm text-gray-600">Total Sales</div>
            <div class="text-4xl font-bold text-green-600">Rp <?php echo number_format($total_sales, 0, ',', '.'); ?></div>
          </div>
          <div class="p-6 bg-white rounded-lg shadow">
            <div class="mb-2 text-sm text-gray-600">Average Transaction</div>
            <div class="text-4xl font-bold text-blue-600">Rp <?php echo number_format($average_transaction, 0, ',', '.'); ?></div>
          </div>
        </div>

        <!-- Transaction History Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Transaction Details</h3>
          </div>
          <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 font-semibold text-gray-700">No</th>
                <th class="px-6 py-3 font-semibold text-gray-700">Transaction ID</th>
                <th class="px-6 py-3 font-semibold text-gray-700">Customer</th>
                <th class="px-6 py-3 font-semibold text-gray-700">Date</th>
                <th class="px-6 py-3 font-semibold text-gray-700">Total</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php
              $no = 1;
              while ($row = $result->fetch_assoc()) {
                $transId = str_pad($row['transaction_id'], 5, '0', STR_PAD_LEFT);
                $date = date('d/m/Y H:i', strtotime($row['transaction_date']));
              ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4"><?php echo $no++; ?></td>
                  <td class="px-6 py-4">TRX-<?php echo $transId; ?></td>
                  <td class="px-6 py-4"><?php echo htmlspecialchars($row['customer_name']); ?></td>
                  <td class="px-6 py-4"><?php echo $date; ?></td>
                  <td class="px-6 py-4 font-semibold">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php if ($result->num_rows == 0) { ?>
            <div class="p-6 text-center text-gray-500">
              No transactions found for the selected period.
            </div>
          <?php } ?>
        </div>
      </div>
    </main>
  </div>
</body>

</html>