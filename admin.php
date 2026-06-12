<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = "admin";
include './connect.php';

$admin_id = $_SESSION['id_admin'];
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE admin_id = $admin_id"));

$view = $_GET['view'] ?? 'profile';
$success = '';
$error = '';

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
  $fullname = htmlspecialchars($_POST['fullname'] ?? '');

  if (empty($fullname)) {
    $error = "Full name is required!";
  } else {
    $update_query = "UPDATE admins SET fullname = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $fullname, $admin_id);
    if ($stmt->execute()) {
      $_SESSION['fullname'] = $fullname;
      $admin['fullname'] = $fullname;
      $success = "Profile updated successfully!";
    } else {
      $error = "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
  }
}

// Handle Change Password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'change_password') {
  $current_password = $_POST['current_password'] ?? '';
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Verify current password
  $verify_stmt = $conn->prepare("SELECT password FROM admins WHERE admin_id = ?");
  $verify_stmt->bind_param("i", $admin_id);
  $verify_stmt->execute();
  $verify_result = $verify_stmt->get_result();
  $verify_row = $verify_result->fetch_assoc();

  if ($verify_row['password'] !== $current_password) {
    $error = "Current password is incorrect!";
  } elseif (empty($new_password)) {
    $error = "New password is required!";
  } elseif ($new_password !== $confirm_password) {
    $error = "Passwords do not match!";
  } elseif (strlen($new_password) < 6) {
    $error = "Password must be at least 6 characters!";
  } else {
    $update_pass_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
    $update_pass_stmt->bind_param("si", $new_password, $admin_id);
    if ($update_pass_stmt->execute()) {
      $success = "Password changed successfully!";
      $view = 'profile';
    } else {
      $error = "Error changing password!";
    }
    $update_pass_stmt->close();
  }
  $verify_stmt->close();
}
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
            <span class="text-gray-600">Welcome, <?php echo htmlspecialchars($admin['fullname']); ?></span>
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
                <a href="?view=profile" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 <?php echo ($view == 'profile' || $view == '') ? 'border-blue-600' : 'border-transparent'; ?>">
                  <i class="fas fa-user-circle w-5 mr-3"></i> Profile
                </a>
                <a href="?view=password" class="hover:bg-blue-50 block px-6 py-3 text-gray-700 border-l-4 <?php echo ($view == 'password') ? 'border-blue-600' : 'border-transparent'; ?>">
                  <i class="fas fa-lock w-5 mr-3"></i> Change Password
                </a>
              </nav>
            </div>
          </div>

          <!-- Content Area -->
          <div class="col-span-2">
            <?php if ($success) { ?>
              <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
              </div>
            <?php } ?>
            <?php if ($error) { ?>
              <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <i class="fas fa-times-circle mr-2"></i> <?php echo $error; ?>
              </div>
            <?php } ?>

            <?php if ($view == 'profile' || $view == '') { ?>
              <!-- Profile Section -->
              <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-6 text-2xl font-bold text-gray-800">My Profile</h3>

                <form method="POST">
                  <input type="hidden" name="action" value="update_profile">
                  <div class="space-y-6">
                    <!-- Profile Picture -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Profile Picture</label>
                      <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-20 h-20 text-2xl text-white bg-blue-600 rounded-full">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                    </div>

                    <!-- Username -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Username</label>
                      <input type="text" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled class="w-full px-4 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>

                    <!-- Full Name -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Full Name</label>
                      <input type="text" name="fullname" value="<?php echo htmlspecialchars($admin['fullname']); ?>" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <!-- Role -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
                      <input type="text" value="Administrator" disabled class="w-full px-4 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4">
                      <a href="admin.php" class="hover:bg-gray-50 px-6 py-2 font-semibold text-gray-700 border border-gray-300 rounded-lg">Cancel</a>
                      <button type="submit" class="hover:bg-blue-700 px-6 py-2 font-semibold text-white bg-blue-600 rounded-lg">Save Changes</button>
                    </div>
                  </div>
                </form>
              </div>
            <?php } elseif ($view == 'password') { ?>
              <!-- Password Section -->
              <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-6 text-2xl font-bold text-gray-800">Change Password</h3>

                <form method="POST" class="max-w-md">
                  <input type="hidden" name="action" value="change_password">
                  <div class="space-y-6">
                    <!-- Current Password -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Current Password</label>
                      <input type="password" name="current_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <!-- New Password -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">New Password</label>
                      <input type="password" name="new_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                      <small class="text-gray-500">Minimum 6 characters</small>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                      <label class="block mb-2 text-sm font-semibold text-gray-700">Confirm Password</label>
                      <input type="password" name="confirm_password" required class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <!-- Action Buttons -->
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