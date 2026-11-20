<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Stari stupac 'image_url' postoji samo u ranijim verzijama baze.
    public function up(): void
    {
        if (Schema::hasColumn('products', 'image_url')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }

    public function down(): void
    {
        // namjerno prazno: stupac 'image_url' se više ne koristi
    }
};
