<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fertilizer_types', function (Blueprint $table) {
            $table->id();
            $table->string('fertilizer_name', 100);
            $table->string('fertilizer_code', 100)->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('subsidized_price', 10, 2)->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_subsidized')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fertilizer_types');
    }
};
