<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_products', function (Blueprint $table) {
            $table->id();
            
            // Inventory Management
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorders')->default(false);
            $table->boolean('sold_individually')->default(false);
            
            // Shipping Information
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('shipping_class')->nullable();
            
            // Physical attributes
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country_of_origin')->nullable();
            
            // Warranty & Returns
            $table->boolean('has_warranty')->default(false);
            $table->integer('warranty_period_months')->nullable();
            $table->text('warranty_details')->nullable();
            $table->boolean('returnable')->default(true);
            $table->integer('return_period_days')->default(7);
            
            $table->timestamps();

            // Indexes
            $table->index('stock_quantity');
            $table->index('brand');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('physical_products');
    }
};
