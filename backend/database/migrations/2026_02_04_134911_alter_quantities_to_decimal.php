<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Količine u kg (npr. 0.5) zato decimalni zapis umjesto cijelog broja.
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('quantity', 10, 3)->change();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('quantity', 10, 3)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
    }
};
