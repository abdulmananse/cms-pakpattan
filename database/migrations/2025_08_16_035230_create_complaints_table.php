<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('complaint_no', 30)->unique()->index();
            $table->string('name')->index();
            $table->bigInteger('cnic')->nullable()->index();
            $table->bigInteger('mobile')->nullable()->index();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('description', 500)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('attachment', 30)->nullable();
            $table->dateTime('complaint_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('assigned_by')->nullable();
            $table->integer('department_id')->nullable();
            $table->enum('source', ['Call Center', 'Online Form', 'Mobile App', 'Email', 'Walk-in Office Entry'])->nullable();
            $table->boolean('complaint_status')->default(0)->comment('0: Pending, 1: Resolved, 2: Rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
