<?php
session_start();
$page = $title = 'transactions';
?>
<!DOCTYPE html>
<html lang="en">

<?php include './head.php' ?>

<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Top Bar -->
      <header class="bg-white shadow">
        <div class="flex items-center justify-between px-8 py-4">
          <h2 class="text-xl font-semibold text-gray-800">New Transaction</h2>
          <div class="flex items-center gap-4">
            <span class="text-gray-600">Welcome, Admin</span>
            <button class="flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full">
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
            <div class="p-6 bg-white rounded-lg shadow">
              <h3 class="mb-6 text-xl font-bold text-gray-800">New Transaction</h3>

              <!-- Customer Selection -->
              <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-gray-700">Customer</label>
                <select class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
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
                <label class="block mb-2 text-sm font-semibold text-gray-700">Transaction Date</label>
                <input type="date" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
              </div>

              <!-- Add Product Section -->
              <div class="mb-6">
                <h4 class="mb-4 font-semibold text-gray-800">Add Product</h4>

                <div class="mb-4">
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Select Product</label>
                  <select class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Select Product</option>
                    <option value="1">Indomie Goreng - Rp 3,500</option>
                    <option value="2">Teh Botol Sosro - Rp 4,000</option>
                    <option value="3">Aqua 600ml - Rp 3,000</option>
                    <option value="4">Kopi Kapal Api - Rp 5,000</option>
                    <option value="5">Susu Ultra 250ml - Rp 6,000</option>
                  </select>
                </div>

                <div class="mb-4">
                  <label class="block mb-2 text-sm font-semibold text-gray-700">Quantity</label>
                  <input type="number" value="1" min="1" class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <button class="hover:bg-blue-700 flex items-center justify-center w-full gap-2 px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg">
                  <i class="fas fa-plus"></i> Add
                </button>
              </div>

              <!-- Product List -->
              <div class="mb-6">
                <h4 class="mb-4 font-semibold text-gray-800">Added Products</h4>
                <div class="space-y-3">
                  <div class="bg-gray-50 flex items-center justify-between p-4 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Indomie Goreng</p>
                      <p class="text-sm text-gray-600">Rp 3,500</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 2</span>
                      <span class="font-semibold text-gray-800">Rp 7,000</span>
                      <button class="hover:text-red-700 text-red-600">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                  <div class="bg-gray-50 flex items-center justify-between p-4 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Teh Botol Sosro</p>
                      <p class="text-sm text-gray-600">Rp 4,000</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 1</span>
                      <span class="font-semibold text-gray-800">Rp 4,000</span>
                      <button class="hover:text-red-700 text-red-600">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                  <div class="bg-gray-50 flex items-center justify-between p-4 rounded-lg">
                    <div>
                      <p class="font-semibold text-gray-800">Aqua 600ml</p>
                      <p class="text-sm text-gray-600">Rp 3,000</p>
                    </div>
                    <div class="flex items-center gap-4">
                      <span class="text-gray-700">Qty: 1</span>
                      <span class="font-semibold text-gray-800">Rp 3,000</span>
                      <button class="hover:text-red-700 text-red-600">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-4">
                <button class="hover:bg-gray-50 flex-1 px-4 py-2 font-semibold text-gray-700 border border-gray-300 rounded-lg">Cancel</button>
                <button class="hover:bg-green-700 flex-1 px-4 py-2 font-semibold text-white bg-green-600 rounded-lg">Save Transaction</button>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div>
            <div class="top-8 sticky p-6 bg-white rounded-lg shadow">
              <h4 class="mb-6 text-lg font-bold text-gray-800">Transaction Summary</h4>

              <div class="mb-6 space-y-4">
                <div class="flex justify-between text-gray-700">
                  <span>Subtotal</span>
                  <span>Rp 14,000</span>
                </div>
                <div class="flex justify-between text-gray-700">
                  <span>Tax (10%)</span>
                  <span>Rp 1,400</span>
                </div>
                <div class="flex justify-between pt-4 border-t border-gray-200">
                  <span class="font-bold text-gray-800">Total</span>
                  <span class="text-2xl font-bold text-blue-600">Rp 14,000</span>
                </div>
              </div>

              <div class="bg-blue-50 p-4 rounded-lg">
                <p class="mb-2 text-sm text-gray-600">Payment Method</p>
                <select class="focus:outline-none focus:ring-2 focus:ring-blue-600 w-full px-3 py-2 border border-gray-300 rounded-lg">
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