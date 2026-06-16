<?php
class Product
{
  public ?int $productId;
  public string $productName;
  public float $price;
  public int $stock;

  public function __construct(string $productName, float $price, int $stock = 0, ?int $productId = null)
  {
    $this->productId = $productId;
    $this->productName = $productName;
    $this->price = $price;
    $this->stock = $stock;
  }
}
