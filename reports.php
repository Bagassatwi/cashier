<?php
session_start();
if (empty($_SESSION['status_login'])) {
  header("location: login.php");
  exit();
}
$page = $title = 'reports';

require_once 'controllers/TransactionController.php';
$TC = new TransactionController();

$from_date = $_GET['from_date'] ?? date('Y-m-01');
$to_date = $_GET['to_date'] ?? date('Y-m-d');

// Data extraction through domain layer
$reportData = $TC->getFilteredReports($from_date, $to_date);
$stats = $TC->getReportingStatistics($from_date, $to_date);

$total_transactions = $stats['total_transactions'];
$total_sales = $stats['total_sales'];
$average_transaction = $total_transactions > 0 ? $total_sales / $total_transactions : 0;

include 'views/reports_view.php';
