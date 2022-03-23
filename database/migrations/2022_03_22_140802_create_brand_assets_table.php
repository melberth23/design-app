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
        Schema::create('brand_assets', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->timestamps();
        });

        Schema::table('brand_assets', function($table) {
           $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_assets');
    }
};
