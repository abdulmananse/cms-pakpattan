<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('officer_contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('officer_name', 255);
            $table->string('designation', 255)->nullable();
            $table->unsignedBigInteger('department_id')->index();
            $table->string('office_establishment', 255)->nullable();
            $table->string('primary_mobile', 30);
            $table->string('alternate_phone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedBigInteger('contact_category_id')->index();
            $table->string('lifecycle_status', 20)->default('Active')->index();
            $table->boolean('is_pcm')->default(false)->index();
            $table->boolean('is_favorite')->default(false)->index();
            $table->string('photo', 255)->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');
            $table->foreign('contact_category_id')->references('id')->on('contact_categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_contacts');
    }
};
