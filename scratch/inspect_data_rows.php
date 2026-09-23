<?php

$json = json_decode(file_get_contents(__DIR__ . '/all_sheets_extracted.json'), true);

foreach ($json as $sheetName => $rows) {
    if ($sheetName === 'REKAP JUMLAH KERJASAMA SPWM') continue;
    echo "========================================\n";
    echo "=== SHEET: $sheetName ===\n";
    echo "========================================\n";
    foreach ($rows as $rowNum => $cols) {
        if ($rowNum >= 8) {
            echo "Row $rowNum: " . json_encode($cols, JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
    echo "\n";
}
