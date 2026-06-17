<div class="space-y-6">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-6 border-b-2 border-gray-200">
    <div class="bg-blue-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1"><i class="fas fa-receipt text-blue-600 mr-2"></i>Transaction ID</p>
      <p class="text-lg font-bold text-gray-800">TRX-<?php echo htmlspecialchars(str_pad((string)$transactionOverview['transaction_id'], 5, '0', STR_PAD_LEFT)); ?></p>
    </div>

    <div class="bg-green-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1"><i class="fas fa-calendar text-green-600 mr-2"></i>Date & Time</p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($transactionOverview['transaction_date']))); ?></p>
    </div>

    <div class="bg-orange-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1"><i class="fas fa-user text-orange-600 mr-2"></i>store</p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($transactionOverview['store_name']); ?></p>
    </div>

    <div class="bg-purple-50 p-4 rounded-lg">
      <p class="text-sm font-semibold text-gray-600 mb-1"><i class="fas fa-user-tie text-purple-600 mr-2"></i>Admin</p>
      <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($transactionOverview['admin_name']); ?></p>
    </div>
  </div>

  <div class="bg-gray-50 rounded-lg overflow-hidden">
    <div class="px-6 py-4 bg-linear-to-r from-gray-600 to-gray-700 border-b-2 border-gray-800">
      <h4 class="text-black font-bold text-lg"><i class="fas fa-shopping-bag mr-2"></i>Items (<?php echo (int)$transactionOverview['item_count']; ?>)</h4>
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
        foreach ($lineItems as $detail) {
          $row_count++;
          $bg_class = ($row_count % 2 === 0) ? 'bg-white' : 'bg-gray-50';
        ?>
          <tr class="<?php echo $bg_class; ?> hover:bg-blue-50 transition">
            <td class="px-6 py-4 font-medium text-gray-800"><?php echo htmlspecialchars($detail['product_name']); ?></td>
            <td class="px-6 py-4 text-center text-gray-700 font-semibold"><?php echo (int)$detail['quantity']; ?></td>
            <td class="px-6 py-4 text-right text-gray-700">Rp <?php echo number_format($detail['price'], 0, ',', '.'); ?></td>
            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
      <p class="text-sm text-gray-600 mb-1 font-semibold"><i class="fas fa-box text-blue-600 mr-2"></i>Items</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo (int)$transactionOverview['item_count']; ?></p>
    </div>

    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
      <p class="text-sm text-gray-600 mb-1 font-semibold"><i class="fas fa-credit-card text-green-600 mr-2"></i>Payment</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($paymentTypeString); ?></p>
    </div>

    <div class="bg-linear-to-br from-blue-500 to-blue-600 p-4 rounded-lg col-span-2 md:col-span-1 md:order-last">
      <p class="text-sm text-blue-600 mb-1 font-semibold"><i class="fas fa-receipt text-blue-700 mr-2"></i>Total</p>
      <p class="text-3xl font-bold text-black">Rp <?php echo number_format($transactionOverview['total'], 0, ',', '.'); ?></p>
    </div>
  </div>
</div>