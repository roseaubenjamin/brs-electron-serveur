<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('unique');
            $table->string('path');
            $table->string('attache');
            $table->string('native_id')->nullable();

            $table->integer('user_id')->unsigned()->nullable() ; 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('application_id')->unsigned()->nullable() ; 
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->integer('mobile_id')->unsigned()->nullable() ; 
            $table->foreign('mobile_id')->references('id')->on('mobiles')->onDelete('cascade');

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
        Schema::dropIfExists('notes');
    }
}
