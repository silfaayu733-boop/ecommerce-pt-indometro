<?php
/**
 * Export SQLite database to a MySQL‑compatible SQL dump.
 * Also counts tables and rows.
 */
$root = __DIR__ . '/..';
$sqlitePath = $root . '/database/database.sqlite';
$backupDir = $root . '/backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}
$timestamp = date('Ymd_His');
$dumpFile = $backupDir . "/sqlite_dump_mysql_{$timestamp}.sql";
$out = fopen($dumpFile, 'w');
if (!$out) {
    echo "Failed to open dump file for writing.\n";
    exit(1);
}

$pdo = new PDO('sqlite:' . $sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get table list (exclude internal sqlite tables)
$tablesStmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);
$tableCount = count($tables);
$rowCounts = [];

foreach ($tables as $table) {
    // Get column info
    $colsStmt = $pdo->query("PRAGMA table_info('{$table}')");
    $cols = $colsStmt->fetchAll(PDO::FETCH_ASSOC);
    $colDefs = [];
    $colNames = [];
    foreach ($cols as $col) {
        $name = $col['name'];
        $type = strtoupper($col['type']);
        // Simple type mapping to MySQL compatible types
        if (strpos($type, 'INT') !== false) {
            $mysqlType = 'INT';
        } elseif (strpos($type, 'CHAR') !== false || strpos($type, 'CLOB') !== false || strpos($type, 'TEXT') !== false) {
            $mysqlType = 'TEXT';
        } elseif (strpos($type, 'BLOB') !== false) {
            $mysqlType = 'BLOB';
        } elseif (strpos($type, 'REAL') !== false || strpos($type, 'FLOA') !== false || strpos($type, 'DOUB') !== false) {
            $mysqlType = 'DOUBLE';
        } else {
            $mysqlType = 'TEXT';
        }
        $colDefs[] = "`{$name}` {$mysqlType}" . ($col['pk'] ? ' PRIMARY KEY' : '');
        $colNames[] = "`{$name}`";
    }
    $createStmt = "CREATE TABLE `{$table}` (" . implode(', ', $colDefs) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n";
    fwrite($out, $createStmt);

    // Export data rows
    $dataStmt = $pdo->query("SELECT * FROM `{$table}`");
    $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
    $rowCounts[$table] = count($rows);
    if ($rowCounts[$table] > 0) {
        $columnsList = implode(', ', $colNames);
        foreach ($rows as $row) {
            $values = [];
            foreach ($colNames as $colDef) {
                // strip backticks to get column name
                $colName = trim($colDef, '`');
                $val = $row[$colName];
                if ($val === null) {
                    $values[] = 'NULL';
                } else {
                    // use PDO quote for proper escaping
                    $values[] = $pdo->quote($val);
                }
            }
            $valuesList = implode(', ', $values);
            $insertStmt = "INSERT INTO `{$table}` ({$columnsList}) VALUES ({$valuesList});\n";
            fwrite($out, $insertStmt);
        }
    }
    fwrite($out, "\n");
}

fclose($out);

// Output summary (plain text) for the calling process
echo "EXPORT_SUCCESS\n";
echo "SQLITE_PATH={$sqlitePath}\n";
echo "BACKUP_DIR={$backupDir}\n";
echo "DUMP_FILE={$dumpFile}\n";
echo "TABLE_COUNT={$tableCount}\n";
foreach ($rowCounts as $t => $c) {
    echo "TABLE_{$t}={$c}\n";
}
?>