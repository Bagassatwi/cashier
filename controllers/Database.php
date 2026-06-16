<?php

declare(strict_types=1);
class Database
{
  private static ?mysqli $instance = null;

  public static function getConnection(): mysqli
  {
    if (self::$instance === null) {
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
      self::$instance = new mysqli('localhost', 'root', '', 'mini_cashier');
      self::$instance->set_charset('utf8mb4');
    }
    return self::$instance;
  }
}
