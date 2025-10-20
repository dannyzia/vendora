<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_products', function (Blueprint $table) {
            $table->id();
            
            // File Information
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            
            // License & Access Control
            $table->boolean('requires_license_key')->default(false);
            $table->string('license_key_prefix')->nullable();
            $table->integer('download_limit')->default(5);
            $table->integer('access_period_days')->default(30);
            
            // Download Settings
            $table->boolean('instant_delivery')->default(true);
            $table->integer('download_url_expiry_hours')->default(1);
            
            // Version Control
            $table->string('version')->nullable();
            $table->text('changelog')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            
            // Requirements
            $table->text('system_requirements')->nullable();
            $table->json('compatible_platforms')->nullable();
            
            // Sample/Preview
            $table->string('sample_file_path')->nullable();
            $table->string('preview_url')->nullable();
            
            // External URL
            $table->boolean('is_external')->default(false);
            $table->string('external_url')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index('file_type');
            $table->index('version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_products');
    }
};
