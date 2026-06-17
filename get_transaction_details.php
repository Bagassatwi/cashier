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
  SELECT t.transaction_id, c.store_name, a.fullname as admin_name, t.transaction_date, 
         SUM(td.subtotal) as total, COUNT(td.detail_id) as item_count
  FROM transactions t
  JOIN store c ON t.store_id = c.store_id
  JOIN admins a ON t.admin_id = a.admin_id
  JOIN transaction_details td ON t.transaction_id = td.transaction_id
  WHERE t.transaction_id = $transaction_id
  GROUP BY t.transaction_id
");

if (mysqli_num_rows($trans_query) == 0) {
  http_response_code(404);
  echo '<div class="text-center py-8"><i class="fas fa-exclamation-circle text-red-600 text-3xl mb-2"></i><p class="text-red-600 text-lg">Transaction not found</p></div>';
  exit;
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

// Get payment type from first detail
$payment_query = mysqli_query($conn, "SELECT DISTINCT payment_type FROM transaction_details WHERE transaction_id = $transaction_id LIMIT 1");
$payment_row = mysqli_fetch_assoc($payment_query);
$payment_type = $payment_row['payment_type'] ?? 'Cash';
?>

<div class="space-y-6">
  <!-- Transaction Header Info -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-6 border-b-2 border-gray-200">
    <div class="bg-blue-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1">
        <i class="fas fa-receipt text-blue-600 mr-2"></i>Transaction ID
      </p>
      <p class="text-lg font-bold text-gray-800">TRX-<?php echo htmlspecialchars($transId); ?></p>
    </div>

    <div class="bg-green-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1">
        <i class="fas fa-calendar text-green-600 mr-2"></i>Date & Time
      </p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($date); ?></p>
    </div>

    <div class="bg-orange-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1">
        <i class="fas fa-user text-orange-600 mr-2"></i>store
      </p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($transaction['store_name']); ?></p>
    </div>

    <div class="bg-purple-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1">
        <i class="fas fa-user-tie text-purple-600 mr-2"></i>Admin
      </p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($transaction['admin_name']); ?></p>
    </div>
  </div>

  <!-- Items Table -->
  <div class="bg-gray-50 rounded-lg overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 border-b-2 border-gray-800">
      <h4 class="text-white font-bold text-lg">
        <i class="fas fa-shopping-bag mr-2"></i>Items (<?php echo $transaction['item_count']; ?>)
      </h4>
    </div>

    <table class="w-full">
      <thead class="bg-gray-100 border-b border-gray-300">
        <tr>
          <th class="px-6 py-3 text-left font-bold text-gray-700 text-sm">Product</th>
          <th class="px-6 py-3 text-center font-bold text-gray-700 text-sm">Qty</th>
          <th class="px-6 py-3 text-right font-bold text-gray-700 text-sm">Price</th>
          <th class="px-6 py-3 text-right font-bold text-gray-700 text-sm">Subtotal</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php
        $row_count = 0;
        while ($detail = mysqli_fetch_assoc($details_query)) {
          $row_count++;
          $bg_class = ($row_count % 2 == 0) ? 'bg-white' : 'bg-gray-50';
        ?>
          <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
            <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($detail['product_name']); ?></td>
            <td class="px-6 py-4 text-center text-gray-700 font-semibold"><?php echo $detail['quantity']; ?></td>
            <td class="px-6 py-4 text-right text-gray-700">Rp <?php echo number_format($detail['price'], 0, ',', '.'); ?></td>
            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- Summary Section -->
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
      <p class="text-sm text-gray-600 mb-1 font-semibold">
        <i class="fas fa-box text-blue-600 mr-2"></i>Items
      </p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $transaction['item_count']; ?></p>
    </div>

    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
      <p class="text-sm text-gray-600 mb-1 font-semibold">
        <i class="fas fa-credit-card text-green-600 mr-2"></i>Payment
      </p>
      <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($payment_type); ?></p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-lg col-span-2 md:col-span-1 md:order-last">
      <p class="text-sm text-blue-100 mb-1 font-semibold">
        <i class="fas fa-receipt text-blue-200 mr-2"></i>Total
      </p>
      <p class="text-3xl font-bold text-white">Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
    </div>
  </div>
</div>