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
        Schema::create('file_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->integer('user_id')->default(0);
            $table->integer('read')->default(0);
            $table->timestamps();
        });

        Schema::table('file_notifications', function($table) {
           $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_notifications');
    }
};
