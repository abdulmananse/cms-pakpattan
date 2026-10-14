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
        Schema::create('revenue_complaints', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('complaint_number', 30)->unique();
            $table->string('citizen_name', 255);
            $table->string('father_husband_name', 255)->nullable();
            $table->string('cnic', 20)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('tehsil', 100)->nullable();
            $table->string('subject', 255);
            $table->text('description');
            $table->string('status', 30)->default('open');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('current_holder_id')->constrained('users');
            $table->json('custody_stack');
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->dateTime('closed_at')->nullable();
            $table->text('closing_comments')->nullable();
            $table->foreignId('reopened_by')->nullable()->constrained('users');
            $table->dateTime('reopened_at')->nullable();
            $table->text('reopening_reason')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('creator_id');
            $table->index('current_holder_id');
            $table->index('complaint_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_complaints');
    }
};
