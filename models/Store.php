<?php
class Store
{
  public string $username;
  public string $password;
  public string $fullname;

  public function __construct(string $username, string $password, string $fullname)
  {
    $this->username = $username;
    $this->password = $password;
    $this->fullname = $fullname;
  }
}
