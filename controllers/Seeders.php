<?php

declare(strict_types=1);
require_once 'Database.php';

// Configure error reporting to throw exceptions for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$sqlFile = __DIR__ . '/populated_data2.sql';

if (!file_exists($sqlFile)) {
  fprintf(STDERR, "Error: Seed file not found at %s\n", $sqlFile);
  exit(1);
}

try {
  // Establish connection and select target database
  $db = Database::getConnection();
  $result = $db->query("SELECT DATABASE()");
  $row = $result->fetch_row();
  $current_db = $row[0];

  echo "Connected to database {$current_db}. Starting seeding process...\n";

  $executedCount = 0;

  $sqlContent = file_get_contents($sqlFile);
  if ($db->multi_query($sqlContent)) {
    $executedCount = 0;
    do {
      if ($result = $db->store_result()) {
        $result->free();
      }
      $executedCount++;
    } while ($db->next_result());
  }

  echo "Seeding completed successfully. Processed {$executedCount} query blocks.\n";
} catch (mysqli_sql_exception $e) {
  fprintf(STDERR, "Seeding failed: %s\n", $e->getMessage());
  exit(1);
}
