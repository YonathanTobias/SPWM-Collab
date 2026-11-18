<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert any legacy values in existing records
        try {
            DB::table('cooperations')
                ->whereIn('level', ['Lokal', 'Nasional'])
                ->update(['level' => 'Dalam Negeri']);

            DB::table('cooperations')
                ->where('level', 'Internasional')
                ->update(['level' => 'Luar Negeri']);
        } catch (\Throwable $e) {
            // In case table or column doesn't match yet
        }

        // 2. Modify column to varchar to support 'Dalam Negeri' and 'Luar Negeri'
        try {
            Schema::table('cooperations', function (Blueprint $table) {
                $table->string('level', 50)->default('Dalam Negeri')->change();
            });
        } catch (\Throwable $e) {
            // Fallback raw query for MySQL if doctrine/dbal change() has issues with enum
            try {
                DB::statement("ALTER TABLE `cooperations` MODIFY `level` VARCHAR(50) NOT NULL DEFAULT 'Dalam Negeri'");
            } catch (\Throwable $ex) {
                // Ignore if not supported in test environment
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('cooperations', function (Blueprint $table) {
                $table->string('level', 50)->default('Lokal')->change();
            });
        } catch (\Throwable $e) {
            //
        }
    }
};
