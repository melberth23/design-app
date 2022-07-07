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
        Schema::create('request_type_dimensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_type_id');
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::table('request_type_dimensions', function($table) {
           $table->foreign('request_type_id')->references('id')->on('request_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_type_dimensions');
    }
};
