<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Modifikasi tabel default 'users' untuk mendukung 3 Role
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'staff', 'customer'])->default('customer')->after('password');
        });

        // 2. Tabel Categories (Kategori Produk)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 3. Tabel Products (Master Produk)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->text('image')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Suppliers (Pemasok Barang)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};