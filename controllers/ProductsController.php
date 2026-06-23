<?php
require_once  'Database.php';

class ProductsController
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }
  /**
   * @return array<array{product_id: int, product_name: string, price: float, stock: int}>
   */
  public function getAvailableProducts(): array
  {
    $result = $this->db->query("SELECT product_id, product_name, price, stock FROM products WHERE stock > 0 AND deleted_at IS NULL ORDER BY product_name ASC");
    if (!$result) {
      return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }
  public function getAll(): ?Product
  {
    $stmt = $this->db->prepare("SELECT product_id, product_name, price, stock FROM products");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res) return null;
    return new Product($res['product_name'], (float)$res['price'], (int)$res['stock'], (int)$res['product_id']);
  }
  public function findById(int $id): ?Product
  {
    $stmt = $this->db->prepare("SELECT product_id, product_name, price, stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$res) return null;
    return new Product($res['product_name'], (float)$res['price'], (int)$res['stock'], (int)$res['product_id']);
  }
  public function delete(int $id): bool | string
  {
    $stmt = $this->db->prepare("UPDATE products SET deleted_at = ? WHERE product_id = ?");
    $deleted_at = date("Y-m-d");
    $stmt->bind_param("si", $deleted_at, $id);
    $res = $stmt->execute();
    $stmt->close();

    if (!$res) return $stmt->error;
    return true;
  }

  public function save(Product $product): bool | string
  {
    if ($product->productId === null) {
      $stmt = $this->db->prepare("INSERT INTO products (product_name, price, stock) VALUES (?, ?, ?)");
      $stmt->bind_param("sdi", $product->productName, $product->price, $product->stock);
      $success = $stmt->execute();
      if ($success) $product->productId = $this->db->insert_id;
      else return $stmt->error;
      $stmt->close();
      return $success;
    } else {
      $stmt = $this->db->prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, updated_at = ? WHERE product_id = ?");
      $date = new DateTime();
      $updated_at = $date->format('Y-m-d H:i:s');
      $stmt->bind_param("sdisi", $product->productName, $product->price, $product->stock, $updated_at, $product->productId);
      $success = $stmt->execute();
      $stmt->close();
      return $success;
    }
  }
  /**
   * Fetch products filtered by optional name pattern.
   * @return array<array{product_id: int, product_name: string, price: float, stock: int}>
   */
  public function searchProducts(string $searchTerm = ''): array
  {
    if ($searchTerm !== '') {
      $stmt = $this->db->prepare("SELECT product_id, product_name, price, stock FROM products WHERE product_name LIKE ? AND deleted_at IS NULL ORDER BY product_name ASC");
      $pattern = "%$searchTerm%";
      $stmt->bind_param("s", $pattern);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      return $result;
    }

    $result = $this->db->query("SELECT product_id, product_name, price, stock FROM products ORDER BY product_name ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
  }
}
