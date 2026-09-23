<?php

$excelPath = 'C:\\Users\\Tobias\\Downloads\\MATRIX KERJASAMA TRIWULAN II 2026.xlsx';

$zip = new ZipArchive();
if ($zip->open($excelPath) === TRUE) {
    // 1. Shared strings
    $sharedStrings = [];
    $ssXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($ssXml) {
        $xml = simplexml_load_string($ssXml);
        foreach ($xml->si as $si) {
            if (isset($si->t)) {
                $sharedStrings[] = (string)$si->t;
            } elseif (isset($si->r)) {
                $text = '';
                foreach ($si->r as $r) {
                    $text .= (string)$r->t;
                }
                $sharedStrings[] = $text;
            } else {
                $sharedStrings[] = '';
            }
        }
    }

    // 2. Sheets mapping
    $wbXml = $zip->getFromName('xl/workbook.xml');
    $sheets = [];
    if ($wbXml) {
        $wb = simplexml_load_string($wbXml);
        foreach ($wb->sheets->sheet as $sheet) {
            $name = (string)$sheet['name'];
            $rId = (string)$sheet->attributes('r', true)['id'];
            $sheets[$rId] = $name;
        }
    }

    // Read relationships
    $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
    $sheetFiles = [];
    if ($relsXml) {
        $rels = simplexml_load_string($relsXml);
        foreach ($rels->Relationship as $rel) {
            $id = (string)$rel['Id'];
            $target = (string)$rel['Target'];
            if (isset($sheets[$id])) {
                $sheetFiles[$sheets[$id]] = 'xl/' . $target;
            }
        }
    }

    $allSheetsData = [];

    foreach ($sheetFiles as $sheetName => $filePath) {
        $sheetXml = $zip->getFromName($filePath);
        if (!$sheetXml) continue;

        $sheet = simplexml_load_string($sheetXml);
        $rowsData = [];

        foreach ($sheet->sheetData->row as $row) {
            $rowNum = (int)$row['r'];
            $rowData = [];

            foreach ($row->c as $c) {
                $cellRef = (string)$c['r'];
                $colLetter = preg_replace('/[0-9]/', '', $cellRef);
                $type = (string)$c['t'];
                $val = (string)$c->v;

                if ($type === 's' && isset($sharedStrings[(int)$val])) {
                    $cellValue = $sharedStrings[(int)$val];
                } else {
                    $cellValue = $val;
                }

                $rowData[$colLetter] = trim($cellValue);
            }
            if (!empty($rowData)) {
                $rowsData[$rowNum] = $rowData;
            }
        }
        $allSheetsData[$sheetName] = $rowsData;
    }

    file_put_contents(__DIR__ . '/all_sheets_extracted.json', json_encode($allSheetsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Successfully extracted all " . count($allSheetsData) . " sheets to all_sheets_extracted.json\n";

    $zip->close();
}
