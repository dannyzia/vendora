<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->where('productable_type', 'App\\Models\\DigitalProduct')
            ->update(['productable_type' => 'digital_product']);
    }

    public function down(): void
    {
        DB::table('products')
            ->where('productable_type', 'digital_product')
            ->update(['productable_type' => 'App\\Models\\DigitalProduct']);
    }
};
