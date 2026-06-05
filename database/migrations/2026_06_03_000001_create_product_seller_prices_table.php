<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_seller_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->decimal('seller_price', 18, 8)->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable()->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'seller_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_seller_prices');
    }
};
