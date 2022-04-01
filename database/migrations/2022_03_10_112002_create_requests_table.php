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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('design_type')->default(2)->comment('1=Logo Design, 2=Infographics');
            $table->string('dimensions')->nullable();
            $table->integer('format')->default(2)->comment('1=.MP4, 2=.AEP');
            $table->longText('description');
            $table->integer('user_id')->default(0);
            $table->integer('brand_id')->default(0);
            $table->integer('priority')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('requests');
    }
};
