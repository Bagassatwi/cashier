<?php
session_start();
$page = 'reports';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini Cashier - Reports</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Top Bar -->
      <header class="bg-white shadow">
        <div class="px-8 py-4 flex justify-between items-center">
          <h2 class="text-xl font-semibold text-gray-800">Transaction History</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, Admin</span>
            <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
              <i class="fas fa-user"></i>
            </button>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="p-8">
        <!-- Filters -->
        <div class="grid grid-cols-5 gap-4 mb-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
          </div>
          <div class="flex items-end">
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Filter</button>
          </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-6 mb-8">
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-600 text-sm mb-2">Total Transactions</div>
            <div class="text-4xl font-bold text-gray-800">42</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-600 text-sm mb-2">Total Sales</div>
            <div class="text-4xl font-bold text-green-600">Rp 5,450,000</div>
          </div>
          <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-600 text-sm mb-2">Average Transaction</div>
            <div class="text-4xl font-bold text-blue-600">Rp 129,762</div>
          </div>
        </div>

        <!-- Transaction History Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
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
                <th class="px-6 py-3 font-semibold text-gray-700">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4">TRX-00042</td>
                <td class="px-6 py-4">Budi Santoso</td>
                <td class="px-6 py-4">24/05/2024</td>
                <td class="px-6 py-4 font-semibold">Rp 125,000</td>
                <td class="px-6 py-4">
                  <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">View</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">2</td>
                <td class="px-6 py-4">TRX-00041</td>
                <td class="px-6 py-4">Siti Aminah</td>
                <td class="px-6 py-4">24/05/2024</td>
                <td class="px-6 py-4 font-semibold">Rp 75,000</td>
                <td class="px-6 py-4">
                  <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">View</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">3</td>
                <td class="px-6 py-4">TRX-00040</td>
                <td class="px-6 py-4">Andi Wijaya</td>
                <td class="px-6 py-4">23/05/2024</td>
                <td class="px-6 py-4 font-semibold">Rp 200,000</td>
                <td class="px-6 py-4">
                  <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">View</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">4</td>
                <td class="px-6 py-4">TRX-00039</td>
                <td class="px-6 py-4">Dwiey Lestari</td>
                <td class="px-6 py-4">23/05/2024</td>
                <td class="px-6 py-4 font-semibold">Rp 150,000</td>
                <td class="px-6 py-4">
                  <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">View</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">5</td>
                <td class="px-6 py-4">TRX-00038</td>
                <td class="px-6 py-4">Rudi Hermawan</td>
                <td class="px-6 py-4">23/05/2024</td>
                <td class="px-6 py-4 font-semibold">Rp 95,000</td>
                <td class="px-6 py-4">
                  <button class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">View</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>

</html>