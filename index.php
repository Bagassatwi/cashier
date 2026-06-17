<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'dashboard';

require_once 'controllers/TransactionController.php';
$TC = new TransactionController();

// Populate model metrics payload through the controller abstraction layer
$stats = $TC->getDashboardStatistics();
$recentTransactionsList = $TC->getRecentTransactions(5);

// Structural view instantiation
include 'views/dashboard_view.php';
