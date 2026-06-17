<?php
class TransactionDetail
{
  public ?int $detailId;
  public int $transaction_id;
  public int $product_id;
  public int $quantity;
  public float $subtotal;

  public function __construct(int $transactionId, int $productId, int $quantity, float $subtotal, ?int $detailId = null)
  {
    $this->detailId = $detailId;
    $this->transaction_id = $transactionId;
    $this->product_id = $productId;
    $this->quantity = $quantity;
    $this->subtotal = $subtotal;
  }
}
