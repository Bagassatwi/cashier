<?php
session_start();
$page = $title = 'dashboard';
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
            <span class="text-gray-600">Welcome, Admin</span>
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
              <p class="text-3xl font-bold text-gray-800">25</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-green-100 rounded-lg">
              <i class="fas fa-users text-green-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Customers</p>
              <p class="text-3xl font-bold text-gray-800">18</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-orange-100 rounded-lg">
              <i class="fas fa-exchange-alt text-orange-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Transactions</p>
              <p class="text-3xl font-bold text-gray-800">42</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-center w-12 h-12 text-2xl bg-purple-100 rounded-lg">
              <i class="fas fa-money-bill-wave text-purple-600"></i>
            </div>
            <div>
              <p class="text-sm text-gray-600">Total Sales</p>
              <p class="text-3xl font-bold text-gray-800">Rp 5,450,000</p>
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
                    <button class="hover:bg-blue-700 px-3 py-1 text-xs text-white bg-blue-600 rounded">View</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">2</td>
                  <td class="px-6 py-4">TRX-00041</td>
                  <td class="px-6 py-4">Siti Aminah</td>
                  <td class="px-6 py-4">24/05/2024</td>
                  <td class="px-6 py-4 font-semibold">Rp 75,000</td>
                  <td class="px-6 py-4">
                    <button class="hover:bg-blue-700 px-3 py-1 text-xs text-white bg-blue-600 rounded">View</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">3</td>
                  <td class="px-6 py-4">TRX-00040</td>
                  <td class="px-6 py-4">Andi Wijaya</td>
                  <td class="px-6 py-4">23/05/2024</td>
                  <td class="px-6 py-4 font-semibold">Rp 200,000</td>
                  <td class="px-6 py-4">
                    <button class="hover:bg-blue-700 px-3 py-1 text-xs text-white bg-blue-600 rounded">View</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">4</td>
                  <td class="px-6 py-4">TRX-00039</td>
                  <td class="px-6 py-4">Dwiey Lestari</td>
                  <td class="px-6 py-4">23/05/2024</td>
                  <td class="px-6 py-4 font-semibold">Rp 150,000</td>
                  <td class="px-6 py-4">
                    <button class="hover:bg-blue-700 px-3 py-1 text-xs text-white bg-blue-600 rounded">View</button>
                  </td>
                </tr>
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4">5</td>
                  <td class="px-6 py-4">TRX-00038</td>
                  <td class="px-6 py-4">Rudi Hermawan</td>
                  <td class="px-6 py-4">23/05/2024</td>
                  <td class="px-6 py-4 font-semibold">Rp 95,000</td>
                  <td class="px-6 py-4">
                    <button class="hover:bg-blue-700 px-3 py-1 text-xs text-white bg-blue-600 rounded">View</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>