<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Purchases (Stock-In dari Supplier oleh Staff/Owner)
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('purchase_number')->unique();
            $table->decimal('total', 12, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamp('purchased_at');
            $table->timestamps();
        });

        // 2. Detail Item Pembelian
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 3. Tabel Sales (Stock-Out / Penjualan ke Pelanggan)
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total', 12, 2);
            $table->string('status', 50)->default('completed');
            $table->timestamp('sold_at');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 4. Detail Item Penjualan (Multi-barang)
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};