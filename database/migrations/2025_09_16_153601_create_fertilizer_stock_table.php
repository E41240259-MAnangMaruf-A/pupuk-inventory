<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fertilizer_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fertilizer_type_id')->constrained('fertilizer_types')->onDelete('cascade');
            $table->unsignedInteger('current_stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fertilizer_stocks');
    }
};
