<?php
require_once 'models/Store.php';

class StoreController
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  /**
   * @return array<array{store_id: int, store_name: string}>
   */
  public function getAllStores(): array
  {
    $result = $this->db->query("SELECT * FROM store ORDER BY store_name ASC");
    if (!$result) {
      return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }
}
