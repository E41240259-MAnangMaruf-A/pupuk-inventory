<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('farmer_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->string('farmer_name', 100);
            $table->text('address');
            $table->string('phone_number', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->decimal('land_area', 8, 2)->nullable();
            $table->string('land_location')->nullable(); // Desa/Kecamatan
            $table->enum('land_status', ['milik', 'sewa', 'garap'])->nullable();
            $table->string('main_commodity')->nullable();
            $table->decimal('average_harvest', 8, 2)->nullable(); // ton per musim
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('farmer_submissions');
    }
};