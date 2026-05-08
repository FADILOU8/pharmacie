<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expiration_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->string('medicine_name');
            $table->string('lot_number');
            $table->date('expiration_date');
            $table->integer('days_until_expiration');
            $table->enum('alert_type', ['normal', 'critical', 'expired'])->default('normal');
            $table->enum('status', ['active', 'resolved'])->default('active');
            $table->timestamps();

            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expiration_alerts');
    }
};
