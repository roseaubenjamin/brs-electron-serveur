<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileTable extends Migration
{
    
    public function up()
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable() ; 
            $table->boolean('active')->nullable() ; 
            $table->string('trello')->nullable() ; 
            $table->string('infusionsoft')->nullable() ; 
            $table->integer('user_id')->unsigned()->nullable() ; 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('mobiles');
    }
}
