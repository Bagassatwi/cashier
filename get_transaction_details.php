<?php
session_start();
if (empty($_SESSION['status_login'])) {
  http_response_code(401);
  exit('Unauthorized');
}

include './connect.php';

if (!isset($_GET['transaction_id'])) {
  http_response_code(400);
  exit('Transaction ID required');
}

$transaction_id = intval($_GET['transaction_id']);

// Get transaction info
$trans_query = mysqli_query($conn, "
  SELECT t.transaction_id, c.customer_name, a.fullname as admin_name, t.transaction_date, 
         SUM(td.subtotal) as total
  FROM transactions t
  JOIN customers c ON t.customer_id = c.customer_id
  JOIN admins a ON t.admin_id = a.admin_id
  JOIN transaction_details td ON t.transaction_id = td.transaction_id
  WHERE t.transaction_id = $transaction_id
  GROUP BY t.transaction_id
");

if (mysqli_num_rows($trans_query) == 0) {
  http_response_code(404);
  exit('Transaction not found');
}

$transaction = mysqli_fetch_assoc($trans_query);
$transId = str_pad($transaction['transaction_id'], 5, '0', STR_PAD_LEFT);
$date = date('d/m/Y H:i:s', strtotime($transaction['transaction_date']));
$total = $transaction['total'];

// Get transaction details (items)
$details_query = mysqli_query($conn, "
  SELECT td.detail_id, p.product_name, td.quantity, p.price, td.subtotal, td.payment_type
  FROM transaction_details td
  JOIN products p ON td.product_id = p.product_id
  WHERE td.transaction_id = $transaction_id
  ORDER BY td.detail_id ASC
");

echo '<div class="space-y-4">';

// Transaction header info
echo '<div class="grid grid-cols-2 gap-6 pb-4 border-b border-gray-200">';
echo '<div>';
echo '<p class="text-gray-600">Transaction ID</p>';
echo '<p class="text-lg font-bold text-gray-800">TRX-' . htmlspecialchars($transId) . '</p>';
echo '</div>';
echo '<div>';
echo '<p class="text-gray-600">Date & Time</p>';
echo '<p class="text-lg font-bold text-gray-800">' . htmlspecialchars($date) . '</p>';
echo '</div>';
echo '<div>';
echo '<p class="text-gray-600">Customer</p>';
echo '<p class="text-lg font-bold text-gray-800">' . htmlspecialchars($transaction['customer_name']) . '</p>';
echo '</div>';
echo '<div>';
echo '<p class="text-gray-600">Admin</p>';
echo '<p class="text-lg font-bold text-gray-800">' . htmlspecialchars($transaction['admin_name']) . '</p>';
echo '</div>';
echo '</div>';

// Items table
echo '<div class="overflow-x-auto">';
echo '<table class="w-full text-sm">';
echo '<thead class="bg-gray-100 border-b border-gray-300">';
echo '<tr>';
echo '<th class="px-4 py-2 text-left text-lg font-semibold text-gray-700">Product</th>';
echo '<th class="px-4 py-2 text-center text-lg font-semibold text-gray-700">Quantity</th>';
echo '<th class="px-4 py-2 text-right font-semibold text-lg text-gray-700">Price</th>';
echo '<th class="px-4 py-2 text-right font-semibold text-lg text-gray-700">Subtotal</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody class="divide-y divide-gray-200">';

$itemCount = 0;
while ($detail = mysqli_fetch_assoc($details_query)) {
  $itemCount++;
  echo '<tr class="*:text-lg hover:bg-gray-50">';
  echo '<td class="px-4 py-3 font-medium text-gray-800">' . htmlspecialchars($detail['product_name']) . '</td>';
  echo '<td class="px-4 py-3 text-center text-gray-700">' . $detail['quantity'] . '</td>';
  echo '<td class="px-4 py-3 text-right text-gray-700">Rp ' . number_format($detail['price'], 0, ',', '.') . '</td>';
  echo '<td class="px-4 py-3 text-right font-semibold text-gray-800">Rp ' . number_format($detail['subtotal'], 0, ',', '.') . '</td>';
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

// Summary
echo '<div class="pt-4 text-lg border-t border-gray-200 space-y-2">';
echo '<div class="flex justify-between text-gray-700">';
echo '<span>Items:</span>';
echo '<span class="font-semibold">' . $itemCount . '</span>';
echo '</div>';
echo '<div class="flex justify-between text-lg">';
echo '<span class="font-bold text-gray-800">Total:</span>';
echo '<span class="font-bold text-blue-600">Rp ' . number_format($total, 0, ',', '.') . '</span>';
echo '</div>';

// Get payment type from first detail
$payment_query = mysqli_query($conn, "SELECT DISTINCT payment_type FROM transaction_details WHERE transaction_id = $transaction_id LIMIT 1");
if ($payment_row = mysqli_fetch_assoc($payment_query)) {
  echo '<div class="flex justify-between text-gray-700">';
  echo '<span>Payment Method:</span>';
  echo '<span class="font-semibold">' . htmlspecialchars($payment_row['payment_type']) . '</span>';
  echo '</div>';
}

echo '</div>';
echo '</div>';
