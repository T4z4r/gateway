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
        Schema::create('account_marking_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('cuId')->nullable();
            $table->string('customer_id');
            $table->string('label');
            $table->string('old_label');
            $table->string('reason');
            $table->unsignedBigInteger('maker_id');
            $table->unsignedBigInteger('checker_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_marking_requests');
    }
};
