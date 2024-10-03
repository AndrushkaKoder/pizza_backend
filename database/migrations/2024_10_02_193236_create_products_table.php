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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->decimal('price', 6);
            $table->integer('weight');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('type_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('type_id')
                ->on('product_types')
                ->references('id')
                ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
