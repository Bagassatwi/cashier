<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}

$page = $title = "admin";
require_once 'controllers/AdminController.php';

$AC = new AdminController();
$admin_id = (int)$_SESSION['id_admin'];
$adminModel = $AC->findById($admin_id);

if (!$adminModel) {
  header("location: logout.php");
  exit();
}

$view = $_GET['view'] ?? 'profile';
$success = '';
$error = '';

// Route: Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
  $fullname = htmlspecialchars($_POST['fullname'] ?? '');

  if (empty($fullname)) {
    $error = "Full name is required!";
  } else {
    if ($AC->updateProfile($admin_id, $fullname)) {
      $_SESSION['fullname'] = $fullname;
      $adminModel->fullname = $fullname;
      $success = "Profile updated successfully!";
    } else {
      $error = "Error updating profile.";
    }
  }
}

// Route: Handle Change Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
  $current_password = $_POST['current_password'] ?? '';
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  if ($adminModel->password !== $current_password) {
    $error = "Current password is incorrect!";
  } elseif (empty($new_password)) {
    $error = "New password is required!";
  } elseif ($new_password !== $confirm_password) {
    $error = "Passwords do not match!";
  } elseif (strlen($new_password) < 6) {
    $error = "Password must be at least 6 characters!";
  } else {
    if ($AC->updatePassword($admin_id, $new_password)) {
      $success = "Password changed successfully!";
      $adminModel->password = $new_password;
      $view = 'profile';
    } else {
      $error = "Error changing password!";
    }
  }
}

include 'views/admin_view.php';
