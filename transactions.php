<?php
session_start();
$page = 'transactions';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mini Cashier - New Transaction</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Top Bar -->
      <header class="bg-white shadow">
        <div class="px-8 py-4 flex justify-between items-center">
          <h2 class="text-xl font-semibold text-gray-800">New Transaction</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, Admin</span>
            <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
              <i class="fas fa-user"></i>
            </button>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="p-8">
        <div class="grid grid-cols-3 gap-8">
          <!-- Form Section -->
          <div class="col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-xl font-bold mb-6 text-gray-800">New Transaction</h3>

              <!-- Customer Selection -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Customer</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                  <option value="">Select Customer</option>
                  <option value="1">Budi Santoso</option>
                  <option value="2">Siti Aminah</option>
                  <option value="3">Andi Wijaya</option>
                  <option value="4">Dwiey Lestari</option>
                  <option value="5">Rudi Hermawan</option>
                </select>
              </div>

              <!-- Transaction Date -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Date</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
              </div>

              <!-- Add Product Section -->
              <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Add Product</h4>

                <div class="mb-4">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Select Product</label>
                  <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Select Product</option>
                    <option value="1">Indomie Goreng - Rp 3,500</option>
                    <option value="2">Teh Botol Sosro - Rp 4,000</option>
                    <option value="3">Aqua 600ml - Rp 3,000</option>
                    <option value="4">Kopi Kapal Api - Rp 5,000</option>
                    <option value="5">Susu Ultra 250ml - Rp 6,000</option>
                  </select>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                  <input type="number" value="1" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold flex items-center justify-center gap-2">
                  <i class="fas fa-plus"></i> Add
                </button>
              </div>

              <!-- Product List -->
              <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Added Products</h4>
                <div class="space-y-3">
                  <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Indomie Goreng</p>
                      <p class="text-sm text-gray-600">Rp 3,500</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 2</span>
                      <span class="font-semibold text-gray-800">Rp 7,000</span>
                      <button class="text-red-600 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                  <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Teh Botol Sosro</p>
                      <p class="text-sm text-gray-600">Rp 4,000</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 1</span>
                      <span class="font-semibold text-gray-800">Rp 4,000</span>
                      <button class="text-red-600 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                  <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Aqua 600ml</p>
                      <p class="text-sm text-gray-600">Rp 3,000</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 1</span>
                      <span class="font-semibold text-gray-800">Rp 3,000</span>
                      <button class="text-red-600 hover:text-red-700">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-4">
                <button class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">Cancel</button>
                <button class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">Save Transaction</button>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
              <h4 class="text-lg font-bold text-gray-800 mb-6">Transaction Summary</h4>

              <div class="space-y-4 mb-6">
                <div class="flex justify-between text-gray-700">
                  <span>Subtotal</span>
                  <span>Rp 14,000</span>
                </div>
                <div class="flex justify-between text-gray-700">
                  <span>Tax (10%)</span>
                  <span>Rp 1,400</span>
                </div>
                <div class="border-t border-gray-200 pt-4 flex justify-between">
                  <span class="font-bold text-gray-800">Total</span>
                  <span class="text-2xl font-bold text-blue-600">Rp 14,000</span>
                </div>
              </div>

              <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Payment Method</p>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                  <option>Cash</option>
                  <option>Card</option>
                  <option>Transfer</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>