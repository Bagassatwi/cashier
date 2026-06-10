<?php
// session_start();
$page = $title = "Admin";

include './connect.php';
$query = mysqli_query($conn, "select * from admins");
$result = mysqli_fetch_array($query);

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
          <h2 class="text-xl font-semibold text-gray-800">Admin Settings</h2>
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
        <div class="grid grid-cols-3 gap-8">
          <!-- Admin Menu -->
          <div class="col-span-1">
            <div class="overflow-hidden bg-white rounded-lg shadow">
              <div class="p-4 text-white bg-blue-600">
                <h3 class="font-semibold">Settings</h3>
              </div>
              <nav class="divide-y divide-gray-200">
                <a href="#" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 border-blue-600">
                  <i class="fas fa-user-circle w-5 mr-3"></i> Profile
                </a>
                <a href="#" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 border-transparent">
                  <i class="fas fa-lock w-5 mr-3"></i> Change Password
                </a>
                <a href="#" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 border-transparent">
                  <i class="fas fa-store w-5 mr-3"></i> Store Settings
                </a>
                <a href="#" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 border-transparent">
                  <i class="fas fa-cogs w-5 mr-3"></i> System Settings
                </a>
                <a href="#" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 border-transparent">
                  <i class="fas fa-users-cog w-5 mr-3"></i> User Management
                </a>
              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-span-2">
            <div class="p-6 bg-white rounded-lg shadow">
              <h3 class="mb-6 text-2xl font-bold text-gray-800">My Profile</h3>

              <div class="space-y-6">
                <!-- Profile Picture -->
                <div>
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Profile Picture</label>
                  <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-20 h-20 text-2xl text-white bg-blue-600 rounded-full">
                      <i class="fas fa-user"></i>
                    </div>
                    <button class="hover:bg-gray-50 px-4 py-2 border border-gray-300 rounded-lg">
                      Upload Photo
                    </button>
                  </div>
                </div>

                <!-- Full Name -->
                <div>
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Full Name</label>
                  <input type="text" value="Admin User" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Email -->
                <div>
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                  <input type="email" value="admin@cashier.com" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Phone -->
                <div>
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Phone</label>
                  <input type="text" value="0812345678900" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Role -->
                <div>
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
                  <input type="text" value="Administrator" disabled class="w-full px-4 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                  <button class="hover:bg-gray-50 px-6 py-2 font-semibold text-gray-700 border border-gray-300 rounded-lg">Cancel</button>
                  <button class="hover:bg-blue-700 px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg">Save Changes</button>
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