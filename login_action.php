<?php
include './connect.php';
if ($_POST) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (empty($username)) {
    echo "<script>alert('Username Can't Be Empty!');location.href='login.php';</script>";
  } elseif (empty($password)) {
    echo "<script>alert('Password Can't Be Empty!');location.href='login.php';</script>";
  } else {
    $query =  "select * from admins where username='$username' AND password='$password'";
    echo $query;
    $loginquery = mysqli_query($conn, $query);
    if (mysqli_num_rows($loginquery) > 0) {
      $dt_login = mysqli_fetch_array($loginquery);
      session_start();
      $_SESSION['id_admin'] = $dt_login['admin_id'];
      $_SESSION['username'] = $dt_login['username'];
      $_SESSION['status_login'] = true;
      header("location: index.php");
    } else {
      echo "<script>alert('Username and/or Password doesn't match');location.href='login.php';</script>";
    }
  }
}
