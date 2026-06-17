<?php
enum PaymentType: string
{
  case Cash = 'Cash';
  case Card = 'Card';
}
class Transaction
{
  public ?int $transactionId;
  public int $storeId;
  public int $adminId;
  public ?string $transactionDate;
  public PaymentType $paymentType;
  public DateTime $created_at;
  public DateTime $updated_at;
  public DateTime $deleted_at;

  public function __construct(int $storeId, int $adminId, PaymentType $paymentType, ?int $transactionId = null)
  {
    $now = new DateTime();
    $this->transactionId = $transactionId;
    $this->storeId = $storeId;
    $this->adminId = $adminId;
    $this->paymentType = $paymentType;
    $this->transactionDate = $now->format('Y-m-d H:i:s');
  }
}
