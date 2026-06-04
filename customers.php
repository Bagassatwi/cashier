<?php
session_start();
$page = 'customers';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini Cashier - Customers</title>
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
          <h2 class="text-xl font-semibold text-gray-800">Customers</h2>
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
        <!-- Header with Add Button -->
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Customers</h3>
          <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Customer
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <div class="relative">
            <input type="text" placeholder="Search customer..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
          </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
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
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4">Budi Santoso</td>
                <td class="px-6 py-4">0812345678790</td>
                <td class="px-6 py-4 flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">2</td>
                <td class="px-6 py-4">Siti Aminah</td>
                <td class="px-6 py-4">0812345678790</td>
                <td class="px-6 py-4 flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">3</td>
                <td class="px-6 py-4">Andi Wijaya</td>
                <td class="px-6 py-4">0812345678790</td>
                <td class="px-6 py-4 flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">4</td>
                <td class="px-6 py-4">Dwiey Lestari</td>
                <td class="px-6 py-4">0812345678790</td>
                <td class="px-6 py-4 flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">5</td>
                <td class="px-6 py-4">Rudi Hermawan</td>
                <td class="px-6 py-4">0812345678790</td>
                <td class="px-6 py-4 flex gap-2">
                  <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600">Edit</button>
                  <button class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">Delete</button>
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