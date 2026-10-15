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
        Schema::create('revenue_complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revenue_complaint_id')->constrained('revenue_complaints')->cascadeOnDelete();
            $table->foreignId('revenue_complaint_log_id')->nullable()->constrained('revenue_complaint_logs')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('file_path', 255);
            $table->string('original_name', 255);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('attachment_type', 50)->default('initial');
            $table->timestamps();

            $table->index('revenue_complaint_id');
            $table->index('revenue_complaint_log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_complaint_attachments');
    }
};
