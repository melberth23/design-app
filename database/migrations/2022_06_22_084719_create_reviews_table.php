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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('rate_designer')->default(0);
            $table->integer('com_designer')->default(0);
            $table->longText('experience_to_designer');
            $table->string('work_again_option')->nullable();
            $table->integer('rate_platform')->default(0);
            $table->longText('experience_platform');
            $table->longText('suggestion');
            $table->string('recommend_option')->nullable();
            $table->timestamps();
        });

        Schema::table('reviews', function($table) {
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
