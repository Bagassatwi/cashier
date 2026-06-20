<?php
class Store
{
  public string $store_name;
  public string $phone;
  public string $address;
  public DateTime $created_at;
  public DateTime $updated_at;
  public DateTime $deleted_at;

  public function __construct(string $store_name, string $phone, string $address)
  {
    $this->store_name = $store_name;
    $this->phone = $phone;
    $this->address = $address;
  }
}
