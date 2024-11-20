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
        Schema::create('account_merging_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('recId')->nullable();
            $table->json('existing_ids');
            $table->string('merged_to');
            $table->string('reason');
            $table->unsignedBigInteger('maker_id');
            $table->unsignedBigInteger('checker_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remark')->nullable();
            $table->timestamps();

            // $table->foreign('maker_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('checker_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_merging_requests');
    }
};
