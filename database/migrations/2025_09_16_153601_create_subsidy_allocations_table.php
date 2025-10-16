<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subsidy_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->foreignId('fertilizer_type_id')->constrained('fertilizer_types')->onDelete('cascade');
            $table->unsignedInteger('maximum_quota');
            $table->unsignedInteger('used_quota')->default(0);
            $table->unsignedInteger('remaining_quota');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subsidy_allocations');
    }
};
