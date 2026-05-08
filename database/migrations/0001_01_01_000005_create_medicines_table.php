<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->string('dci');
            $table->string('name');
            $table->string('form');
            $table->string('dosage');
            $table->string('lot_number');
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->date('expiration_date');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();

            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
