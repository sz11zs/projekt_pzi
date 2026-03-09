<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE products MODIFY quantity DECIMAL(10,3) NOT NULL");
        DB::statement("ALTER TABLE order_items MODIFY quantity DECIMAL(10,3) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY quantity INT NOT NULL");
        DB::statement("ALTER TABLE order_items MODIFY quantity INT NOT NULL");
    }
};
