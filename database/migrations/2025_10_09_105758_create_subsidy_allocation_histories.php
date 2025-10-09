<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('subsidy_allocation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subsidy_allocation_id')
                ->constrained('subsidy_allocations')
                ->onDelete('cascade');

            $table->foreignId('fertilizer_type_id')
                ->constrained('fertilizer_types')
                ->onDelete('cascade');

            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->onDelete('set null');

            $table->integer('quantity')->default(0);
            $table->enum('type', ['use', 'restore'])->default('use');
            $table->string('note')->nullable();
            
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
