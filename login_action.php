<?php
session_start();
include './connect.php';

if ($_POST) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($username)) {
    echo "<script>alert('Username Can\\'t Be Empty!');location.href='login.php';</script>";
  } elseif (empty($password)) {
    echo "<script>alert('Password Can\\'t Be Empty!');location.href='login.php';</script>";
  } else {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT admin_id, username, fullname FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $loginquery = $stmt->get_result();

    if ($loginquery->num_rows > 0) {
      $dt_login = $loginquery->fetch_array(MYSQLI_ASSOC);
      $_SESSION['id_admin'] = $dt_login['admin_id'];
      $_SESSION['username'] = $dt_login['username'];
      $_SESSION['fullname'] = $dt_login['fullname'];
      $_SESSION['status_login'] = true;
      $stmt->close();
      header("location: index.php");
      exit();
    } else {
      echo "<script>alert('Username and/or Password doesn\\'t match');location.href='login.php';</script>";
    }
    $stmt->close();
  }
}
