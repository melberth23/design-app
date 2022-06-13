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
        Schema::create('comments_notification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id');
            $table->integer('user_id')->default(0);
            $table->integer('read')->default(0);
            $table->timestamps();
        });

        Schema::table('comments_notification', function($table) {
           $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_notification');
    }
};
