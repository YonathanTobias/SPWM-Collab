<?php

$json = json_decode(file_get_contents(__DIR__ . '/all_sheets_extracted.json'), true);

foreach ($json as $sheetName => $rows) {
    echo "=== SHEET: $sheetName (Total Rows: " . count($rows) . ") ===\n";
    $count = 0;
    foreach ($rows as $rowNum => $cols) {
        if ($count < 6) {
            echo "Row $rowNum: " . json_encode($cols, JSON_UNESCAPED_UNICODE) . "\n";
            $count++;
        }
    }
    echo "\n";
}
