<?php
session_start();
$page = $title = 'products';
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
            <span class="text-gray-600">Welcome, Admin</span>
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
          <button class="hover:bg-blue-700 flex items-center gap-2 px-6 py-2 text-white bg-blue-600 rounded-lg">
            <i class="fas fa-plus"></i> Add Product
          </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
          <div class="relative">
            <input type="text" placeholder="Search product..." class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
            <i class="fas fa-search right-3 top-3 absolute text-gray-400"></i>
          </div>
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
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">1</td>
                <td class="px-6 py-4">Indomie Goreng</td>
                <td class="px-6 py-4">Rp 3,500</td>
                <td class="px-6 py-4">50</td>
                <td class="flex gap-2 px-6 py-4">
                  <button class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</button>
                  <button class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">2</td>
                <td class="px-6 py-4">Teh Botol Sosro</td>
                <td class="px-6 py-4">Rp 4,000</td>
                <td class="px-6 py-4">40</td>
                <td class="flex gap-2 px-6 py-4">
                  <button class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</button>
                  <button class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">3</td>
                <td class="px-6 py-4">Aqua 600ml</td>
                <td class="px-6 py-4">Rp 3,000</td>
                <td class="px-6 py-4">60</td>
                <td class="flex gap-2 px-6 py-4">
                  <button class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</button>
                  <button class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">4</td>
                <td class="px-6 py-4">Kopi Kapal Api</td>
                <td class="px-6 py-4">Rp 5,000</td>
                <td class="px-6 py-4">35</td>
                <td class="flex gap-2 px-6 py-4">
                  <button class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</button>
                  <button class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">5</td>
                <td class="px-6 py-4">Susu Ultra 250ml</td>
                <td class="px-6 py-4">Rp 6,000</td>
                <td class="px-6 py-4">25</td>
                <td class="flex gap-2 px-6 py-4">
                  <button class="hover:bg-yellow-600 px-3 py-1 text-xs text-white bg-yellow-500 rounded">Edit</button>
                  <button class="hover:bg-red-700 px-3 py-1 text-xs text-white bg-red-600 rounded">Delete</button>
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