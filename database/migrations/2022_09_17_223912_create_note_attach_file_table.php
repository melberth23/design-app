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
        Schema::create('note_attach_file', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id');
            $table->string('upload');
            $table->timestamps();
        });

        Schema::table('note_attach_file', function($table) {
           $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_attach_file');
    }
};
