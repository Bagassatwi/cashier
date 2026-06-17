<!DOCTYPE html>
<html lang="en">
<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
    <main class="flex-1">
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

      <div class="p-8">
        <div class="mb-8">
          <h3 class="text-3xl font-bold text-gray-800">Sales Reports</h3>
          <p class="text-gray-600 text-sm mt-1">Track and analyze your transaction history</p>
        </div>

        <form method="GET" class="mb-8 p-6 bg-white rounded-lg shadow-md border-l-4 border-blue-600">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
              <label class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-wide"><i class="fas fa-calendar text-blue-600 mr-2"></i>From Date</label>
              <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium">
            </div>
            <div>
              <label class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-wide"><i class="fas fa-calendar text-blue-600 mr-2"></i>To Date</label>
              <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition font-medium">
            </div>
            <div class="flex gap-2 col-span-1 md:col-span-3">
              <button type="submit" class="flex-1 px-4 py-2 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center justify-center gap-2"><i class="fas fa-search"></i>Filter</button>
              <a href="reports.php" class="flex-1 px-4 py-2 font-bold text-gray-700 border-2 border-gray-300 hover:bg-gray-100 rounded-lg text-center transition flex items-center justify-center gap-2"><i class="fas fa-redo"></i>Reset</a>
            </div>
          </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Total Transactions</h3>
                <div class="p-3 bg-blue-600 text-white rounded-lg"><i class="fas fa-receipt text-lg"></i></div>
              </div>
              <p class="text-4xl font-bold text-gray-800"><?php echo $total_transactions; ?></p>
              <p class="text-xs text-gray-600 mt-2"><i class="fas fa-check-circle text-green-600 mr-1"></i>Completed</p>
            </div>
          </div>

          <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Total Sales</h3>
                <div class="p-3 bg-green-600 text-white rounded-lg"><i class="fas fa-money-bill-wave text-lg"></i></div>
              </div>
              <p class="text-4xl font-bold text-gray-800">Rp <?php echo number_format($total_sales, 0, ',', '.'); ?></p>
              <p class="text-xs text-gray-600 mt-2"><i class="fas fa-chart-line text-green-600 mr-1"></i>Revenue</p>
            </div>
          </div>

          <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Avg Transaction</h3>
                <div class="p-3 bg-orange-600 text-white rounded-lg"><i class="fas fa-calculator text-lg"></i></div>
              </div>
              <p class="text-4xl font-bold text-gray-800">Rp <?php echo number_format($average_transaction, 0, ',', '.'); ?></p>
              <p class="text-xs text-gray-600 mt-2"><i class="fas fa-info-circle text-orange-600 mr-1"></i>Per transaction</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-6 border-b border-gray-300">
            <div class="flex items-center gap-3">
              <i class="fas fa-history text-white text-2xl"></i>
              <h3 class="text-xl font-bold text-white">Transaction History</h3>
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
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <?php
                $no = 1;
                foreach ($reportData as $row) {
                  $transId = str_pad((string)$row['transaction_id'], 5, '0', STR_PAD_LEFT);
                  $date = date('d/m/Y H:i', strtotime($row['transaction_date']));
                  $bg_class = ($no % 2 === 0) ? 'bg-white' : 'bg-gray-50';
                ?>
                  <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $no++; ?></td>
                    <td class="px-6 py-4 font-bold text-blue-600">TRX-<?php echo $transId; ?></td>
                    <td class="px-6 py-4 text-gray-800"><?php echo htmlspecialchars($row['store_name']); ?></td>
                    <td class="px-6 py-4 text-gray-700"><?php echo $date; ?></td>
                    <td class="px-6 py-4 font-bold text-green-600 text-right">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <?php if (count($reportData) === 0) { ?>
            <div class="p-12 text-center text-gray-500">
              <i class="fas fa-inbox text-5xl mb-3 opacity-50"></i>
              <p class="text-lg font-semibold">No transactions found</p>
              <p class="text-sm">Try adjusting the date range or reset the filter</p>
            </div>
          <?php } ?>
        </div>
      </div>
    </main>
  </div>
</body>

</html>