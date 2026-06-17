<?php
class Admin
{
  public ?string $admins_id;
  public string $username;
  public string $password;
  public string $fullname;
  public DateTime $created_at;
  public DateTime $updated_at;
  public DateTime $deleted_at;

  public function __construct(string $username, string $password, string $fullname, ?string $admins_id)
  {
    $this->admins_id = $admins_id;
    $this->username = $username;
    $this->password = $password;
    $this->fullname = $fullname;
  }
}
