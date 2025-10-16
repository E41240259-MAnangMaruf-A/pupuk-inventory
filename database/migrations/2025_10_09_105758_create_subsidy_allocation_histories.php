<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('subsidy_allocation_histories', function (Blueprint $table) {
            $table->id();

            // Relasi utama
            $table->foreignId('subsidy_allocation_id')
                ->constrained('subsidy_allocations')
                ->onDelete('cascade');

            $table->foreignId('fertilizer_type_id')
                ->constrained('fertilizer_types')
                ->onDelete('cascade');

            // Relasi opsional ke transaksi (jika berasal dari transaksi penjualan)
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->onDelete('set null');

            // Data kuota subsidi per perubahan
            $table->unsignedInteger('current_quota')->default(0); // sebelum perubahan
            $table->integer('quota_change')->default(0);           // perubahan (+/-)
            $table->integer('final_quota')->default(0);            // setelah perubahan
            $table->integer('quantity')->default(0);               // jumlah yang digunakan/dikembalikan

            // Jenis pergerakan kuota subsidi
            $table->enum('type', ['use', 'restore'])->default('use');

            // Catatan
            $table->string('note')->nullable();

            // Pengguna yang melakukan aksi
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsidy_allocation_histories');
    }
};
