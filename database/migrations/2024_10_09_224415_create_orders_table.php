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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('payment_id');
            $table->dateTime('delivery_time');
            $table->integer('total_sum');
            $table->string('address');
            $table->boolean('closed')->default(false);
            $table->timestamps();

            $table->foreign('payment_id')
                ->on('payments')
                ->references('id');

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('status_id')
                ->on('statuses')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
