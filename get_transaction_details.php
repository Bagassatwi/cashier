<?php
session_start();
if (empty($_SESSION['status_login'])) {
  http_response_code(401);
  exit('Unauthorized');
}

if (!isset($_GET['transaction_id'])) {
  http_response_code(400);
  exit('Transaction ID required');
}

require_once 'controllers/TransactionController.php';
$TC = new TransactionController();

$transaction_id = intval($_GET['transaction_id']);

// Fetch transactional domain data payloads isolated from view blocks
$transactionOverview = $TC->getTransactionOverview($transaction_id);

if (!$transactionOverview) {
  http_response_code(404);
  echo '<div class="text-center py-8"><i class="fas fa-exclamation-circle text-red-600 text-3xl mb-2"></i><p class="text-red-600 text-lg">Transaction not found</p></div>';
  exit();
}

$lineItems = $TC->getTransactionLineItems($transaction_id);
$paymentTypeString = $TC->getPaymentType($transaction_id);

// Include view implementation fragment
include 'views/transaction_details_modal_view.php';
