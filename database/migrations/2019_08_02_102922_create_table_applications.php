<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApplications extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name') ; 
            $table->text('accessToken')->nullable() ; 
            $table->string('url')->nullable() ; 
            $table->string('type')->nullable() ; 
            $table->string('unique') ; 
            $table->integer('user_id')->unsigned()->nullable() ; 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
