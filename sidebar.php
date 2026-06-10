<aside class="w-64 text-white bg-gray-900 shadow-lg">
  <div class="p-6 border-b border-gray-700">
    <h1 class="flex items-center gap-2 text-2xl font-bold">
      <i class="fas fa-shopping-cart"></i> Mini Cashier
    </h1>
  </div>
  <nav class="px-3 mt-6 space-y-2">
    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'dashboard' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-home w-5"></i> Dashboard
    </a>
    <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'products' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-box w-5"></i> Products
    </a>
    <a href="customers.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'customers' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-users w-5"></i> Customers
    </a>
    <a href="transactions.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'transactions' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-exchange-alt w-5"></i> Transactions
    </a>
    <a href="reports.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'reports' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-chart-bar w-5"></i> Reports
    </a>
    <a href="admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo $page == 'admin' ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 transition'; ?>">
      <i class="fas fa-cog w-5"></i> Admin
    </a>
  </nav>
  <div class="absolute bottom-0 left-0 right-0 w-64 p-3 border-t border-gray-700">
    <a href="logout.php" class="hover:bg-gray-800 flex items-center gap-3 px-4 py-3 text-red-400 transition rounded-lg">
      <i class="fas fa-sign-out-alt w-5"></i> Logout
    </a>
  </div>
</aside>