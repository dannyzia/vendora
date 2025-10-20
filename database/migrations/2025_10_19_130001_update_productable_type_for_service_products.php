<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')
            ->where('productable_type', 'App\\Models\\ServiceProduct')
            ->update(['productable_type' => 'service_product']);
    }

    public function down(): void
    {
        DB::table('products')
            ->where('productable_type', 'service_product')
            ->update(['productable_type' => 'App\\Models\\ServiceProduct']);
    }
};
