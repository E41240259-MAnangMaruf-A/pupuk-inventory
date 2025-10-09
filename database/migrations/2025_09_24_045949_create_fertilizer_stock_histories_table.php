<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fertilizer_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fertilizer_type_id')->constrained('fertilizer_types')->onDelete('cascade');
            $table->unsignedInteger('current_stock')->default(0);
            $table->integer('stock_change')->default(0);
            $table->integer('final_stock')->default(0);
            $table->enum('type', ['in', 'out'])->default('in');
            $table->string('note')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_stock_histories');
    }
};
