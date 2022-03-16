<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('business_recurring_plans_id')->nullable();
            $table->string('plan')->nullable();
            $table->string('cycle')->nullable();
            $table->string('currency')->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_methods')->nullable();
            $table->longText('payment_url');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
