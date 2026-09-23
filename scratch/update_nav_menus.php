<?php

use App\Models\NavMenu;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$updated = NavMenu::where('url', '/dashboard')->update(['title' => 'Visualisasi Data & Rekapitulasi Akreditasi']);
echo "Updated $updated NavMenu records.\n";
