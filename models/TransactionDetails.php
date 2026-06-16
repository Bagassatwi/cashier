<?php
class TransactionDetail
{
  public ?int $detailId;
  public int $transactionId;
  public int $productId;
  public int $quantity;
  public float $subtotal;

  public function __construct(int $transactionId, int $productId, int $quantity, float $subtotal, ?int $detailId = null)
  {
    $this->detailId = $detailId;
    $this->transactionId = $transactionId;
    $this->productId = $productId;
    $this->quantity = $quantity;
    $this->subtotal = $subtotal;
  }
}
