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
   * Fetch aggregate administrative statistics.
   * @return array{products: int, stores: int, transactions: int, sales: float}
   */
  public function getDashboardStatistics(): array
  {
    $stats = ['products' => 0, 'stores' => 0, 'transactions' => 0, 'sales' => 0.0];

    $resProd = $this->db->query("SELECT COUNT(*) as count FROM products");
    if ($resProd) $stats['products'] = (int)$resProd->fetch_assoc()['count'];

    $resStore = $this->db->query("SELECT COUNT(*) as count FROM store");
    if ($resStore) $stats['stores'] = (int)$resStore->fetch_assoc()['count'];

    $resTx = $this->db->query("SELECT COUNT(*) as count FROM transactions");
    if ($resTx) $stats['transactions'] = (int)$resTx->fetch_assoc()['count'];

    $resSales = $this->db->query("SELECT SUM(subtotal) as total FROM transaction_details");
    if ($resSales) $stats['sales'] = (float)($resSales->fetch_assoc()['total'] ?? 0.0);

    return $stats;
  }

  /**
   * Fetch the most recent transactions with store associations.
   * @param int $limit
   * @return array<array{transaction_id: int, store_name: string, transaction_date: string, total: float}>
   */
  public function getRecentTransactions(int $limit = 5): array
  {
    $stmt = $this->db->prepare("
      SELECT t.transaction_id, c.store_name, t.transaction_date, SUM(td.subtotal) as total
      FROM transactions t
      JOIN store c ON t.store_id = c.store_id
      JOIN transaction_details td ON t.transaction_id = td.transaction_id
      GROUP BY t.transaction_id
      ORDER BY t.transaction_date DESC
      LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result;
  }

  /**
   * Fetch a singular transaction context with structural metadata.
   * @param int $transactionId
   * @return array{transaction_id: int, store_name: string, admin_name: string, transaction_date: string, total: float, item_count: int}|null
   */
  public function getTransactionOverview(int $transactionId): ?array
  {
    $stmt = $this->db->prepare("
      SELECT t.transaction_id, c.store_name, a.fullname as admin_name, t.transaction_date, 
             SUM(td.subtotal) as total, COUNT(td.detail_id) as item_count
      FROM transactions t
      JOIN store c ON t.store_id = c.store_id
      JOIN admins a ON t.admin_id = a.admin_id
      JOIN transaction_details td ON t.transaction_id = td.transaction_id
      WHERE t.transaction_id = ?
      GROUP BY t.transaction_id
    ");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $res ?: null;
  }

  /**
   * Fetch individual breakdown records for items contained inside a target transaction.
   * @param int $transactionId
   * @return array<array{detail_id: int, product_name: string, quantity: int, price: float, subtotal: float}>
   */
  public function getTransactionLineItems(int $transactionId): array
  {
    $stmt = $this->db->prepare("
      SELECT td.detail_id, p.product_name, td.quantity, p.price, td.subtotal
      FROM transaction_details td
      JOIN products p ON td.product_id = p.product_id
      WHERE td.transaction_id = ?
      ORDER BY td.detail_id ASC
    ");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
  }

  /**
   * Extract distinct payment methods applied to a transaction.
   * @param int $transactionId
   * @return string
   */
  public function getPaymentType(int $transactionId): string
  {
    $stmt = $this->db->prepare("SELECT payment_type FROM transactions WHERE transaction_id = ? LIMIT 1");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $res['payment_type'] ?? 'Cash';
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
  /**
   * Fetch a filtered collection of transactions within a date range.
   * @return array<array{transaction_id: int, store_name: string, transaction_date: string, total: float}>
   */
  public function getFilteredReports(string $fromDate, string $toDate): array
  {
    $stmt = $this->db->prepare("
      SELECT t.transaction_id, c.store_name, t.transaction_date, SUM(td.subtotal) as total
      FROM transactions t
      JOIN store c ON t.store_id = c.store_id
      JOIN transaction_details td ON t.transaction_id = td.transaction_id
      WHERE DATE(t.transaction_date) >= ? AND DATE(t.transaction_date) <= ?
      GROUP BY t.transaction_id
      ORDER BY t.transaction_date DESC
    ");
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result;
  }

  /**
   * Fetch aggregate reporting metrics within a date range.
   * @return array{total_transactions: int, total_sales: float}
   */
  public function getReportingStatistics(string $fromDate, string $toDate): array
  {
    $stmt = $this->db->prepare("
      SELECT COUNT(DISTINCT t.transaction_id) as total_transactions, SUM(td.subtotal) as total_sales
      FROM transactions t
      JOIN transaction_details td ON t.transaction_id = td.transaction_id
      WHERE DATE(t.transaction_date) >= ? AND DATE(t.transaction_date) <= ?
    ");
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return [
      'total_transactions' => (int)($res['total_transactions'] ?? 0),
      'total_sales' => (float)($res['total_sales'] ?? 0.0)
    ];
  }
}
