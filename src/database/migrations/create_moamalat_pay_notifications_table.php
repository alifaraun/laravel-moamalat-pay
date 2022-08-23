<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoamalatPayNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moamalat_pay_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('MerchantId', 190)->nullable();
            $table->string('TerminalId', 190)->nullable();
            $table->string('DateTimeLocalTrxn', 190)->nullable();
            $table->string('TxnType', 190)->nullable();
            $table->string('Message', 190)->nullable();
            $table->string('PaidThrough', 190)->nullable();
            $table->string('SystemReference', 190)->nullable();
            $table->string('NetworkReference', 190)->nullable();
            $table->string('MerchantReference', 190)->nullable();
            $table->string('Amount', 190)->nullable();
            $table->string('Currency', 190)->nullable();
            $table->string('PayerAccount', 190)->nullable();
            $table->string('PayerName', 190)->nullable();
            $table->string('ActionCode', 190)->nullable();
            $table->json('request')->nullable();
            $table->string('ip', 190)->nullable();
            $table->boolean('verified');
            $table->timestamps();

            $table->index(['verified', 'NetworkReference', 'MerchantReference', 'MerchantId', 'TerminalId'], 'verified_networkRef_merchantRef_mid_tid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moamalat_pay_notifications');
    }
};
