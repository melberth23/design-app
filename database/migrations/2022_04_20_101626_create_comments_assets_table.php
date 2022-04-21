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
        Schema::create('comments_assets', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('comment_id');
            $table->timestamps();
        });

        Schema::table('comments_assets', function($table) {
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
        Schema::dropIfExists('comments_assets');
    }
};
