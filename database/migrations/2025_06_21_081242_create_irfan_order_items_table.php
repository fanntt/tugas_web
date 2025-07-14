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
        Schema::create('irfan_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('irfan_order_id')->constrained('irfan_orders')->onDelete('cascade');
            $table->foreignId('irfan_product_id')->constrained('irfan_products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irfan_order_items');
    }
};
