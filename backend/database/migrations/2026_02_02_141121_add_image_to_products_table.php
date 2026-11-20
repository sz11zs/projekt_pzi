<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Stupac 'image' već stvara create_products_table; ova migracija ga dodaje
    // samo u starim bazama gdje ga još nema.
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'image')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('image')->nullable()->after('price');
            });
        }
    }

    public function down(): void
    {
        // namjerno prazno: 'image' pripada create_products_table
    }
};
