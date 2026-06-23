<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$sqlFile = __DIR__ . '/Database.sql';

if (!file_exists($sqlFile)) {
  fprintf(STDERR, "Error: SQL file missing at %s\n", $sqlFile);
  exit(1);
}

try {
  $db = new mysqli('localhost', 'root', '');

  // Initialize Database
  $db->query("DROP DATABASE IF EXISTS mini_cashier");
  $db->query("CREATE DATABASE IF NOT EXISTS mini_cashier");
  $db->query("USE mini_cashier");
  echo "Database 'mini_cashier' initialized.\n";

  // Read external SQL contents
  $sqlScript = file_get_contents($sqlFile);

  echo "Importing SQL schema script...\n";

  // Execute multiple SQL statements concurrently
  if ($db->multi_query($sqlScript)) {
    do {
      // Flush and discard results from individual queries to clear the buffer
      if ($result = $db->store_result()) {
        $result->free();
      }
    } while ($db->next_result());
  }

  echo "Migration executed successfully. All tables generated in database 'mini_cashier'.\n";
} catch (mysqli_sql_exception $e) {
  fprintf(STDERR, "Migration failed: %s\n", $e->getMessage());
  exit(1);
} finally {
  if (isset($db) && $db instanceof mysqli) {
    $db->close();
  }
}
