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
        Schema::create('account_details', function (Blueprint $table) {
            $table->id();
            //Additional Fields
            $table->string('cuId')->nullable();
            $table->string('label')->nullable();
            $table->string('deadId')->default('N');
            $table->boolean('checkDuplicate');

            //XML Details
            $table->timestamp('txn_date_and_time')->nullable();
            $table->string('channel_id', 50)->nullable();
            $table->string('type', 10)->nullable();
            $table->string('acct_type', 10)->nullable();
            $table->string('customer_id');
            $table->string('customer_ic')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_currency', 10)->nullable();
            $table->string('language', 10)->nullable();
            $table->date('account_opened_date')->nullable();
            $table->string('txn_product_code', 10)->nullable();
            $table->string('risk_level', 2)->nullable();
            $table->string('screening', 1)->nullable();
            $table->string('account_customer_first_name', 100)->nullable();
            $table->string('account_customer_middle_name', 100)->nullable();
            $table->string('account_customer_last_name', 100)->nullable();
            $table->string('account_customer_gender')->nullable();
            $table->string('martial_status', 10)->nullable();
            $table->string('id_type', 10)->nullable();
            $table->string('id_number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('district', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('branch', 10)->nullable();
            $table->string('country_of_birth', 50)->nullable();
            $table->string('account_mobile_number', 20)->nullable();
            $table->string('customer_type', 10)->nullable();
            $table->date('id_issue_date')->nullable();
            $table->string('id_expiry_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('customer_photo', 1)->nullable();
            $table->string('customer_signature', 1)->nullable();
            $table->string('account_email_address', 100)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_details');
    }
};
