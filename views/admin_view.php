<!DOCTYPE html>
<html lang="en">
<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <?php include 'sidebar.php'; ?>
    <main class="flex-1">
      <header class="bg-white shadow">
        <div class="flex items-center justify-between px-8 py-4">
          <h2 class="text-xl font-semibold text-gray-800">Admin Settings</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($adminModel->fullname); ?></span>
            <button class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
              <i class="fas fa-user"></i>
            </button>
          </div>
        </div>
      </header>

      <div class="p-8">
        <div class="grid grid-cols-3 gap-8">
          <div class="col-span-1">
            <div class="overflow-hidden bg-white rounded-lg shadow">
              <div class="p-4 text-white bg-blue-600">
                <h3 class="font-semibold">Settings</h3>
              </div>
              <nav class="divide-y divide-gray-200">
                <a href="?view=profile" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 <?php echo ($view === 'profile' || $view === '') ? 'border-blue-600' : 'border-transparent'; ?>">
                  <i class="fas fa-user-circle w-5 mr-3"></i> Profile
                </a>
                <a href="?view=password" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 <?php echo ($view === 'password') ? 'border-blue-600' : 'border-transparent'; ?>">
                  <i class="fas fa-lock w-5 mr-3"></i> Change Password
                </a>
              </nav>
            </div>
          </div>

          <div class="col-span-2">
            <?php if ($success) { ?>
              <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i> <?php echo htmlspecialchars($success); ?>
              </div>
            <?php } ?>
            <?php if ($error) { ?>
              <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <i class="fas fa-times-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
              </div>
            <?php } ?>

            <?php if ($view === 'profile' || $view === '') { ?>
              <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-6 text-2xl font-bold text-gray-800">My Profile</h3>
                <form method="POST">
                  <input type="hidden" name="action" value="update_profile">
                  <div class="space-y-6">
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Profile Picture</label>
                      <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-20 h-20 text-2xl text-white bg-blue-600 rounded-full">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                    </div>
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Username</label>
                      <input type="text" value="<?php echo htmlspecialchars($adminModel->username); ?>" disabled class="w-full px-4 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Full Name</label>
                      <input type="text" name="fullname" value="<?php echo htmlspecialchars($adminModel->fullname); ?>" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
                      <input type="text" value="Administrator" disabled class="w-full px-4 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>
                    <div class="flex gap-4 pt-4">
                      <a href="admin.php" class="hover:bg-gray-50 px-6 py-2 font-semibold text-gray-700 border border-gray-300 rounded-lg">Cancel</a>
                      <button type="submit" class="hover:bg-blue-700 px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg">Save Changes</button>
                    </div>
                  </div>
                </form>
              </div>
            <?php } elseif ($view === 'password') { ?>
              <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-6 text-2xl font-bold text-gray-800">Change Password</h3>
                <form method="POST" class="max-w-md">
                  <input type="hidden" name="action" value="change_password">
                  <div class="space-y-6">
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Current Password</label>
                      <input type="password" name="current_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">New Password</label>
                      <input type="password" name="new_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                      <small class="text-gray-500">Minimum 6 characters</small>
                    </div>
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Confirm Password</label>
                      <input type="password" name="confirm_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="flex gap-4 pt-4">
                      <a href="admin.php" class="hover:bg-gray-50 px-6 py-2 font-semibold text-gray-700 border border-gray-300 rounded-lg">Cancel</a>
                      <button type="submit" class="hover:bg-blue-700 px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg">Change Password</button>
                    </div>
                  </div>
                </form>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>