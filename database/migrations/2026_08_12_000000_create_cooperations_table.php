<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cooperations', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('partner_name');
            $table->string('document_number');
            $table->enum('document_type', ['MoU', 'MoA', 'IA']);
            $table->enum('level', ['Lokal', 'Nasional', 'Internasional']);
            $table->text('scope');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Aktif', 'Akan Berakhir', 'Kedaluwarsa', 'Dalam Proses Perpanjangan'])->default('Aktif');
            $table->boolean('is_public')->default(true);
            $table->string('file_path')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperations');
    }
};
