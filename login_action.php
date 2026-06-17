<?php
session_start();
require_once 'controllers/AdminController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($username)) {
    echo "<script>alert('Username Can\\'t Be Empty!');location.href='login.php';</script>";
    exit();
  } elseif (empty($password)) {
    echo "<script>alert('Password Can\\'t Be Empty!');location.href='login.php';</script>";
    exit();
  }

  $AC = new AdminController();
  $admin = $AC->authenticate($username, $password);

  if ($admin !== null) {
    $_SESSION['id_admin'] = (int)$admin->admins_id;
    $_SESSION['username'] = $admin->username;
    $_SESSION['fullname'] = $admin->fullname;
    $_SESSION['status_login'] = true;
    header("location: index.php");
    exit();
  } else {
    echo "<script>alert('Username and/or Password doesn\\'t match');location.href='login.php';</script>";
    exit();
  }
}
