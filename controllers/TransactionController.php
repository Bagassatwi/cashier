<?php
require_once  'Database.php';
class TransactionController
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }
  /**
   * @param Transaction $tx
   * @param array<array{product_id: int, quantity: int, price: float}> $items $items
   */
  public function save(Transaction $tx, array $items): bool
  {
    if ($tx->transactionId === null) {
      $this->db->begin_transaction();
      $stmt = $this->db->prepare("INSERT INTO transactions (store_id, admin_id, payment_type) VALUES (?, ?, ?)");
      $payment_type = $tx->paymentType->value;
      $stmt->bind_param("iis", $tx->storeId, $tx->adminId, $payment_type);
      $stmtDetail = $this->db->prepare("INSERT INTO transaction_details (transaction_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
      $success = $stmt->execute();
      $trans_id = $this->db->insert_id;
      foreach ($items as $item) {
        $subtotal = $item['quantity'] * $item['price'];
        $stmtDetail->bind_param("iiii", $trans_id, $item['product_id'], $item['quantity'], $subtotal);
        $success = $stmtDetail->execute();
      }
      $success = $this->db->commit();
      if ($success) $tx->transactionId = $this->db->insert_id;
      $stmt->close();
      return $success;
    }
    return false;
  }
}
