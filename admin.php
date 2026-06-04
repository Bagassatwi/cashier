<?php
session_start();
$page = 'admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini Cashier - Admin</title>
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
          <h2 class="text-xl font-semibold text-gray-800">Admin Settings</h2>
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
        <div class="grid grid-cols-3 gap-8">
          <!-- Admin Menu -->
          <div class="col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <div class="bg-blue-600 text-white p-4">
                <h3 class="font-semibold">Settings</h3>
              </div>
              <nav class="divide-y divide-gray-200">
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 border-l-4 border-blue-600">
                  <i class="fas fa-user-circle w-5 mr-3"></i> Profile
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 border-l-4 border-transparent">
                  <i class="fas fa-lock w-5 mr-3"></i> Change Password
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 border-l-4 border-transparent">
                  <i class="fas fa-store w-5 mr-3"></i> Store Settings
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 border-l-4 border-transparent">
                  <i class="fas fa-cogs w-5 mr-3"></i> System Settings
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 border-l-4 border-transparent">
                  <i class="fas fa-users-cog w-5 mr-3"></i> User Management
                </a>
              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h3>

              <div class="space-y-6">
                <!-- Profile Picture -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Picture</label>
                  <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl">
                      <i class="fas fa-user"></i>
                    </div>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                      Upload Photo
                    </button>
                  </div>
                </div>

                <!-- Full Name -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                  <input type="text" value="Admin User" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                  <input type="email" value="admin@cashier.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Phone -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                  <input type="text" value="0812345678900" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Role -->
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                  <input type="text" value="Administrator" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                  <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">Cancel</button>
                  <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Save Changes</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>