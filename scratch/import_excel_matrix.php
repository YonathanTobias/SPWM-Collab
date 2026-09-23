<?php

use App\Models\Cooperation;
use Carbon\Carbon;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jsonPath = __DIR__ . '/all_sheets_extracted.json';
if (!file_exists($jsonPath)) {
    die("all_sheets_extracted.json not found!\n");
}

$allSheets = json_decode(file_get_contents($jsonPath), true);

function excelDateToCarbon($val) {
    if (empty($val)) return Carbon::today();
    if (is_numeric($val)) {
        $days = (float)$val;
        if ($days < 1000) return Carbon::today();
        // Excel base date 1899-12-30
        $timestamp = ($days - 25569) * 86400;
        return Carbon::createFromTimestamp($timestamp);
    }
    try {
        return Carbon::parse($val);
    } catch (\Exception $e) {
        return Carbon::today();
    }
}

// Clear old cooperations
Cooperation::truncate();
echo "Cleared old cooperations table.\n";

$importedCount = 0;

foreach ($allSheets as $sheetName => $rows) {
    if (str_contains($sheetName, 'REKAP JUMLAH KERJASAMA')) continue;

    // Determine default level based on sheet name
    $level = 'Lokal';
    if (str_contains($sheetName, 'Luar Negeri')) {
        $level = 'Internasional';
    } elseif (str_contains($sheetName, 'Industri') || str_contains($sheetName, 'Universitas') || str_contains($sheetName, 'Sekolah')) {
        $level = 'Nasional';
    }

    foreach ($rows as $rowNum => $cols) {
        // Skip header rows
        if ($rowNum < 8) continue;
        if (isset($cols['A']) && strtolower($cols['A']) === 'no') continue;
        if (isset($cols['B']) && (strtolower($cols['B']) === 'nama institusi' || strtolower($cols['B']) === 'nama mitra')) continue;

        $partnerName = trim($cols['B'] ?? '');
        if (empty($partnerName)) continue;

        $mouTitle = trim($cols['C'] ?? '');
        $moaTitle = trim($cols['D'] ?? '');
        $scopeProdi = trim($cols['E'] ?? '');
        $durationStr = trim($cols['F'] ?? '3 tahun');
        $startDateVal = trim($cols['G'] ?? '');
        $endDateVal = trim($cols['H'] ?? '');
        $linkDoc = trim($cols['I'] ?? '');
        $notes = trim($cols['J'] ?? '');

        // Determine Document Type (MoU vs MoA vs IA)
        $docType = 'MoA';
        if (!empty($mouTitle) && empty($moaTitle)) {
            $docType = 'MoU';
        } elseif (empty($mouTitle) && !empty($moaTitle)) {
            $docType = 'MoA';
        } elseif (!empty($mouTitle) && !empty($moaTitle)) {
            $docType = 'MoA';
        } else {
            $docType = 'IA';
        }

        // Clean title & scope strings
        $titleParts = array_filter([$mouTitle, $moaTitle]);
        $rawTitle = !empty($titleParts) ? implode(' - ', $titleParts) : "Kerjasama " . $partnerName;
        // Clean multi-newlines
        $title = preg_replace('/\s+/', ' ', $rawTitle);

        $scopeParts = array_filter([$scopeProdi, $notes, $linkDoc ? "Link Dokumen: " . $linkDoc : null]);
        $rawScope = !empty($scopeParts) ? implode(" | ", $scopeParts) : "Kerjasama Tri Dharma Perguruan Tinggi STIKes Panti Waluya Malang.";
        $scope = preg_replace('/\s+/', ' ', $rawScope);

        $startDate = excelDateToCarbon($startDateVal);
        $endDate = excelDateToCarbon($endDateVal);

        // Generated document number
        $docNum = "STIKES-PW/" . strtoupper($docType) . "/" . $startDate->format('Y') . "/" . str_pad($importedCount + 1, 3, '0', STR_PAD_LEFT);

        // Dynamic Status calculation
        $today = Carbon::today();
        if ($endDate < $today) {
            $status = 'Kedaluwarsa';
        } elseif ($endDate <= $today->copy()->addDays(90)) {
            $status = 'Akan Berakhir';
        } else {
            $status = 'Aktif';
        }

        Cooperation::create([
            'title' => mb_substr($title, 0, 1000),
            'partner_name' => mb_substr($partnerName, 0, 255),
            'document_number' => $docNum,
            'document_type' => $docType,
            'level' => $level,
            'scope' => mb_substr($scope, 0, 5000),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'status' => $status,
            'is_public' => true,
            'file_path' => 'documents/sample_mou_rsud.pdf',
            'document_link' => filter_var($linkDoc, FILTER_VALIDATE_URL) ? $linkDoc : null,
            'contact_person' => 'Tim LPPM / Humas ' . mb_substr($partnerName, 0, 100),
            'contact_email' => 'kerjasama@stikespantiwaluya.ac.id',
        ]);

        $importedCount++;
        echo "Imported #$importedCount: [$docType] [$level] $partnerName (Berlaku s/d {$endDate->format('d/m/Y')})\n";
    }
}

echo "\n============================================\n";
echo "SUCCESS! Total Imported Cooperations: $importedCount\n";
echo "============================================\n";
