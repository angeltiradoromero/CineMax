<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('movie_session_id')
                ->constrained('movie_sessions')
                ->restrictOnDelete();

            $table->foreignId('seat_id')
                ->constrained('seats')
                ->restrictOnDelete();

            $table->uuid('booking_group');

            $table->decimal('price', 8, 2);

            $table->string('status')->default('pending');

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['movie_session_id', 'seat_id', 'status']);
            $table->index('booking_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
