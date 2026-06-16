<?php
include 'Database.php';

class ProductsController
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = Database::getConnection();
  }

  public function getAll(int $id): ?Product
  {
    $stmt = $this->db->prepare("SELECT product_id, product_name, price, stock FROM products");
    $stmt->bind_param("i", $id);
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
    $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
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
      $stmt = $this->db->prepare("UPDATE products SET product_name = ?, price = ?, stock = ? WHERE product_id = ?");
      $stmt->bind_param("sdii", $product->productName, $product->price, $product->stock, $product->productId);
      $success = $stmt->execute();
      $stmt->close();
      return $success;
    }
  }
}
