<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('landmark_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->string('payment_method')->default('card');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('confirmed');
            $table->string('booking_reference')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
